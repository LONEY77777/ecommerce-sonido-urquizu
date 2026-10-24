<?php

use App\Enums\OrderStatus;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

beforeEach(function () {
    $this->customer = User::factory()->create(['name' => 'Demo Customer']);
    $this->product = Product::factory()->inStock(10)->create(['price' => 25.50]);
});

/**
 * A valid set of delivery details for the checkout form.
 *
 * @return array<string, string>
 */
function checkoutDetails(array $overrides = []): array
{
    return array_merge([
        'customer_name' => 'Demo Customer',
        'phone' => '+8801712345678',
        'address' => '42 Elephant Road, Dhaka 1205',
        'notes' => 'Please deliver after 5pm.',
    ], $overrides);
}

it('requires authentication to reach checkout', function () {
    $this->get(route('checkout'))->assertRedirect(route('login'));
    $this->post(route('orders.store'), checkoutDetails())->assertRedirect(route('login'));
});

it('sends a customer with an empty cart back to the cart page', function () {
    $this->actingAs($this->customer)
        ->get(route('checkout'))
        ->assertRedirect(route('cart.index'))
        ->assertSessionHas('error');
});

it('shows the checkout form with an order summary', function () {
    Cart::factory()->for($this->customer)->for($this->product)->create(['quantity' => 2]);

    $this->actingAs($this->customer)
        ->get(route('checkout'))
        ->assertSuccessful()
        ->assertSee($this->product->name)
        ->assertSee('51.00'); // 2 x 25.50
});

it('places an order from the cart', function () {
    Cart::factory()->for($this->customer)->for($this->product)->create(['quantity' => 2]);

    $response = $this->actingAs($this->customer)
        ->post(route('orders.store'), checkoutDetails());

    $order = Order::sole();

    $response->assertRedirect(route('orders.show', $order))->assertSessionHas('success');

    expect($order->user_id)->toBe($this->customer->id)
        ->and($order->status)->toBe(OrderStatus::Pending)
        ->and((float) $order->total_amount)->toBe(51.00)
        ->and($order->order_number)->toStartWith('SH-')
        ->and($order->customer_name)->toBe('Demo Customer')
        ->and($order->notes)->toBe('Please deliver after 5pm.');
});

it('copies each cart line onto the order, capturing the price paid', function () {
    Cart::factory()->for($this->customer)->for($this->product)->create(['quantity' => 3]);

    $this->actingAs($this->customer)->post(route('orders.store'), checkoutDetails());

    $this->assertDatabaseHas('order_items', [
        'product_id' => $this->product->id,
        'product_name' => $this->product->name,
        'quantity' => 3,
        'price' => '25.50',
    ]);
});

it('decrements product stock when an order is placed', function () {
    Cart::factory()->for($this->customer)->for($this->product)->create(['quantity' => 4]);

    $this->actingAs($this->customer)->post(route('orders.store'), checkoutDetails());

    expect($this->product->refresh()->stock)->toBe(6);
});

it('empties the cart after a successful order', function () {
    Cart::factory()->for($this->customer)->for($this->product)->create(['quantity' => 2]);

    $this->actingAs($this->customer)->post(route('orders.store'), checkoutDetails());

    $this->assertDatabaseCount('carts', 0);
});

it('generates a unique order number for every order', function () {
    $this->actingAs($this->customer);

    Cart::factory()->for($this->customer)->for($this->product)->create(['quantity' => 1]);
    $this->post(route('orders.store'), checkoutDetails());

    Cart::factory()->for($this->customer)->for($this->product)->create(['quantity' => 1]);
    $this->post(route('orders.store'), checkoutDetails());

    expect(Order::pluck('order_number')->unique())->toHaveCount(2);
});

it('rejects checkout with missing delivery details', function (string $field) {
    Cart::factory()->for($this->customer)->for($this->product)->create();

    $this->actingAs($this->customer)
        ->post(route('orders.store'), checkoutDetails([$field => '']))
        ->assertInvalid($field);

    $this->assertDatabaseCount('orders', 0);
})->with(['customer_name', 'phone', 'address']);

it('accepts checkout without notes', function () {
    Cart::factory()->for($this->customer)->for($this->product)->create();

    $this->actingAs($this->customer)
        ->post(route('orders.store'), checkoutDetails(['notes' => '']))
        ->assertSessionHasNoErrors();

    expect(Order::sole()->notes)->toBeNull();
});

it('refuses checkout when stock ran out after the item was added to the cart', function () {
    $cart = Cart::factory()->for($this->customer)->for($this->product)->create(['quantity' => 5]);
    $this->product->update(['stock' => 2]);

    $this->actingAs($this->customer)
        ->post(route('orders.store'), checkoutDetails())
        ->assertInvalid('cart');

    $this->assertDatabaseCount('orders', 0);
    $this->assertDatabaseHas('carts', ['id' => $cart->id]);
});

it('refuses checkout with an empty cart', function () {
    $this->actingAs($this->customer)
        ->post(route('orders.store'), checkoutDetails())
        ->assertInvalid('cart');

    $this->assertDatabaseCount('orders', 0);
});

it('lists a customer’s own orders newest first', function () {
    $older = Order::factory()->for($this->customer)->create(['created_at' => now()->subWeek()]);
    $newer = Order::factory()->for($this->customer)->create();

    $this->actingAs($this->customer)
        ->get(route('orders.index'))
        ->assertSuccessful()
        ->assertSeeInOrder([$newer->order_number, $older->order_number]);
});

it('shows an order with its status and line items', function () {
    $order = Order::factory()->for($this->customer)->status(OrderStatus::Processing)->create();
    $order->items()->create([
        'product_id' => $this->product->id,
        'product_name' => $this->product->name,
        'quantity' => 2,
        'price' => 25.50,
    ]);

    $this->actingAs($this->customer)
        ->get(route('orders.show', $order))
        ->assertSuccessful()
        ->assertSee($order->order_number)
        ->assertSee('Processing')
        ->assertSee($this->product->name);
});

it('does not let a customer view another customer’s order', function () {
    $otherOrder = Order::factory()->for(User::factory())->create();

    $this->actingAs($this->customer)
        ->get(route('orders.show', $otherOrder))
        ->assertForbidden();
});

it('lets an admin view any order through the storefront route', function () {
    $order = Order::factory()->for($this->customer)->create();

    $this->actingAs(User::factory()->admin()->create())
        ->get(route('orders.show', $order))
        ->assertSuccessful();
});
