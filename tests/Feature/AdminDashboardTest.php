<?php

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
});

it('counts products, orders and customers', function () {
    Product::factory()->count(4)->create();
    User::factory()->count(3)->create();
    Order::factory()->count(2)->create();

    $this->actingAs($this->admin)
        ->get(route('admin.dashboard'))
        ->assertSuccessful()
        ->assertViewHas('totalProducts', 4)
        ->assertViewHas('totalOrders', 2)
        // Three seeded customers plus the two order owners; the admin is excluded.
        ->assertViewHas('totalUsers', 5);
});

it('counts revenue from completed orders only', function () {
    Order::factory()->status(OrderStatus::Completed)->create(['total_amount' => 100.00]);
    Order::factory()->status(OrderStatus::Completed)->create(['total_amount' => 50.50]);
    Order::factory()->status(OrderStatus::Pending)->create(['total_amount' => 999.00]);
    Order::factory()->status(OrderStatus::Cancelled)->create(['total_amount' => 999.00]);

    $this->actingAs($this->admin)
        ->get(route('admin.dashboard'))
        ->assertViewHas('totalRevenue', 150.50);
});

it('breaks orders down by status, including statuses with no orders', function () {
    Order::factory()->count(2)->status(OrderStatus::Pending)->create();
    Order::factory()->status(OrderStatus::Completed)->create();

    $this->actingAs($this->admin)
        ->get(route('admin.dashboard'))
        ->assertViewHas('ordersByStatus', [
            'Pending' => 2,
            'Processing' => 0,
            'Completed' => 1,
            'Cancelled' => 0,
        ]);
});

it('builds a zero-filled 14 day revenue series', function () {
    Order::factory()->status(OrderStatus::Completed)->create(['total_amount' => 75.00]);

    $this->actingAs($this->admin)
        ->get(route('admin.dashboard'))
        ->assertViewHas('revenueByDay', function (array $series) {
            return count($series) === 14
                && $series[now()->format('M j')] === 75.00;
        });
});

it('surfaces products running low on stock', function () {
    $low = Product::factory()->create(['name' => 'Nearly Gone', 'stock' => 2]);
    Product::factory()->create(['name' => 'Plenty Left', 'stock' => 500]);

    $this->actingAs($this->admin)
        ->get(route('admin.dashboard'))
        ->assertSee('Nearly Gone')
        ->assertViewHas('lowStockProducts', fn ($products) => $products->contains($low) && $products->count() === 1);
});

it('shows the most recent orders', function () {
    $recent = Order::factory()->create(['customer_name' => 'Nadia Rahman']);

    $this->actingAs($this->admin)
        ->get(route('admin.dashboard'))
        ->assertSee($recent->order_number)
        ->assertSee('Nadia Rahman');
});
