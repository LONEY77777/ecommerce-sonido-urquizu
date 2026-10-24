<?php

use App\Models\Order;
use App\Models\Product;
use App\Models\User;

/**
 * Every admin route, paired with the HTTP verb used to reach it.
 *
 * @return list<array{string, string}>
 */
dataset('admin routes', function () {
    return [
        'dashboard' => ['get', fn () => route('admin.dashboard')],
        'product index' => ['get', fn () => route('admin.products.index')],
        'product create' => ['get', fn () => route('admin.products.create')],
        'product store' => ['post', fn () => route('admin.products.store')],
        'product edit' => ['get', fn () => route('admin.products.edit', Product::factory()->create())],
        'product update' => ['put', fn () => route('admin.products.update', Product::factory()->create())],
        'product destroy' => ['delete', fn () => route('admin.products.destroy', Product::factory()->create())],
        'order index' => ['get', fn () => route('admin.orders.index')],
        'order show' => ['get', fn () => route('admin.orders.show', Order::factory()->create())],
        'order status' => ['patch', fn () => route('admin.orders.status', Order::factory()->create())],
        'user index' => ['get', fn () => route('admin.users.index')],
        'user show' => ['get', fn () => route('admin.users.show', User::factory()->create())],
        'user destroy' => ['delete', fn () => route('admin.users.destroy', User::factory()->create())],
    ];
});

it('redirects guests to the login page', function (string $method, Closure $url) {
    $this->{$method}($url())->assertRedirect(route('login'));
})->with('admin routes');

it('forbids customers from reaching the admin panel', function (string $method, Closure $url) {
    $this->actingAs(User::factory()->create())
        ->{$method}($url())
        ->assertForbidden();
})->with('admin routes');

it('lets an admin reach the dashboard', function () {
    $this->actingAs(User::factory()->admin()->create())
        ->get(route('admin.dashboard'))
        ->assertSuccessful();
});
