<?php

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
});

it('lists every order with the columns the admin needs', function () {
    $order = Order::factory()->create([
        'customer_name' => 'Nadia Rahman',
        'phone' => '+8801712345678',
        'total_amount' => 120.50,
    ]);

    $this->actingAs($this->admin)
        ->get(route('admin.orders.index'))
        ->assertSuccessful()
        ->assertSee($order->order_number)
        ->assertSee('Nadia Rahman')
        ->assertSee('+8801712345678')
        ->assertSee('120.50');
});

it('searches orders by order number, customer name and phone', function () {
    $match = Order::factory()->create(['customer_name' => 'Nadia Rahman', 'phone' => '+8801700000001']);
    $other = Order::factory()->create(['customer_name' => 'Someone Else', 'phone' => '+8801700000002']);

    $this->actingAs($this->admin);

    $this->get(route('admin.orders.index', ['search' => 'Nadia']))
        ->assertSee($match->order_number)
        ->assertDontSee($other->order_number);

    $this->get(route('admin.orders.index', ['search' => $match->order_number]))
        ->assertSee($match->order_number)
        ->assertDontSee($other->order_number);

    $this->get(route('admin.orders.index', ['search' => '+8801700000001']))
        ->assertSee($match->order_number)
        ->assertDontSee($other->order_number);
});

it('filters orders by status', function () {
    $pending = Order::factory()->status(OrderStatus::Pending)->create();
    $completed = Order::factory()->status(OrderStatus::Completed)->create();

    $this->actingAs($this->admin)
        ->get(route('admin.orders.index', ['status' => 'pending']))
        ->assertSee($pending->order_number)
        ->assertDontSee($completed->order_number);
});

it('shows an order with its customer details and line items', function () {
    $product = Product::factory()->create(['name' => 'Rhodia Dot Pad']);
    $customer = User::factory()->create(['name' => 'Nadia Rahman', 'email' => 'nadia@example.com']);

    $order = Order::factory()->for($customer)->create([
        'customer_name' => 'Nadia Rahman',
        'phone' => '+8801712345678',
        'address' => '42 Elephant Road, Dhaka',
    ]);

    $order->items()->create([
        'product_id' => $product->id,
        'product_name' => $product->name,
        'quantity' => 3,
        'price' => 9.75,
    ]);

    $this->actingAs($this->admin)
        ->get(route('admin.orders.show', $order))
        ->assertSuccessful()
        ->assertSee('Nadia Rahman')
        ->assertSee('nadia@example.com')
        ->assertSee('+8801712345678')
        ->assertSee('42 Elephant Road, Dhaka')
        ->assertSee('Rhodia Dot Pad')
        ->assertSee('9.75');
});

it('updates an order status', function (OrderStatus $status) {
    $order = Order::factory()->status(OrderStatus::Pending)->create();

    $this->actingAs($this->admin)
        ->from(route('admin.orders.show', $order))
        ->patch(route('admin.orders.status', $order), ['status' => $status->value])
        ->assertRedirect(route('admin.orders.show', $order))
        ->assertSessionHas('success');

    expect($order->refresh()->status)->toBe($status);
})->with([
    'processing' => OrderStatus::Processing,
    'completed' => OrderStatus::Completed,
    'cancelled' => OrderStatus::Cancelled,
    'pending' => OrderStatus::Pending,
]);

it('rejects an unknown order status', function () {
    $order = Order::factory()->status(OrderStatus::Pending)->create();

    $this->actingAs($this->admin)
        ->patch(route('admin.orders.status', $order), ['status' => 'refunded'])
        ->assertInvalid('status');

    expect($order->refresh()->status)->toBe(OrderStatus::Pending);
});

it('does not let a customer change an order status', function () {
    $order = Order::factory()->status(OrderStatus::Pending)->create();

    $this->actingAs(User::factory()->create())
        ->patch(route('admin.orders.status', $order), ['status' => 'completed'])
        ->assertForbidden();

    expect($order->refresh()->status)->toBe(OrderStatus::Pending);
});
