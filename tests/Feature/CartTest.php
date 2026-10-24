<?php

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;

beforeEach(function () {
    $this->customer = User::factory()->create();
    $this->product = Product::factory()->inStock(20)->create(['price' => 10.00]);
});

it('requires authentication for every cart route', function () {
    $cart = Cart::factory()->create();

    $this->get(route('cart.index'))->assertRedirect(route('login'));
    $this->post(route('cart.store'), ['product_id' => $this->product->id])->assertRedirect(route('login'));
    $this->patch(route('cart.update', $cart), ['quantity' => 2])->assertRedirect(route('login'));
    $this->delete(route('cart.destroy', $cart))->assertRedirect(route('login'));
});

it('adds a product to the cart', function () {
    $this->actingAs($this->customer)
        ->from(route('products.show', $this->product))
        ->post(route('cart.store'), ['product_id' => $this->product->id, 'quantity' => 3])
        ->assertRedirect(route('products.show', $this->product))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('carts', [
        'user_id' => $this->customer->id,
        'product_id' => $this->product->id,
        'quantity' => 3,
    ]);
});

it('defaults the quantity to one', function () {
    $this->actingAs($this->customer)
        ->post(route('cart.store'), ['product_id' => $this->product->id]);

    $this->assertDatabaseHas('carts', ['product_id' => $this->product->id, 'quantity' => 1]);
});

it('merges repeat additions into one cart line', function () {
    $this->actingAs($this->customer);

    $this->post(route('cart.store'), ['product_id' => $this->product->id, 'quantity' => 2]);
    $this->post(route('cart.store'), ['product_id' => $this->product->id, 'quantity' => 3]);

    expect(Cart::where('user_id', $this->customer->id)->count())->toBe(1);

    $this->assertDatabaseHas('carts', ['product_id' => $this->product->id, 'quantity' => 5]);
});

it('refuses to add more units than are in stock', function () {
    $product = Product::factory()->inStock(2)->create();

    $this->actingAs($this->customer)
        ->post(route('cart.store'), ['product_id' => $product->id, 'quantity' => 5])
        ->assertInvalid('quantity');

    $this->assertDatabaseCount('carts', 0);
});

it('counts what is already in the cart against available stock', function () {
    $product = Product::factory()->inStock(5)->create();

    $this->actingAs($this->customer);
    $this->post(route('cart.store'), ['product_id' => $product->id, 'quantity' => 4]);
    $this->post(route('cart.store'), ['product_id' => $product->id, 'quantity' => 3])->assertInvalid('quantity');

    $this->assertDatabaseHas('carts', ['product_id' => $product->id, 'quantity' => 4]);
});

it('shows cart lines with quantities and totals', function () {
    Cart::factory()->for($this->customer)->for($this->product)->create(['quantity' => 3]);

    $this->actingAs($this->customer)
        ->get(route('cart.index'))
        ->assertSuccessful()
        ->assertSee($this->product->name)
        ->assertSee('30.00'); // 3 x 10.00
});

it('updates the quantity of a cart line', function () {
    $cart = Cart::factory()->for($this->customer)->for($this->product)->create(['quantity' => 1]);

    $this->actingAs($this->customer)
        ->from(route('cart.index'))
        ->patch(route('cart.update', $cart), ['quantity' => 4])
        ->assertRedirect(route('cart.index'));

    expect($cart->refresh()->quantity)->toBe(4);
});

it('refuses to update a cart line beyond available stock', function () {
    $product = Product::factory()->inStock(3)->create();
    $cart = Cart::factory()->for($this->customer)->for($product)->create(['quantity' => 1]);

    $this->actingAs($this->customer)
        ->patch(route('cart.update', $cart), ['quantity' => 9])
        ->assertInvalid('quantity');

    expect($cart->refresh()->quantity)->toBe(1);
});

it('removes a cart line', function () {
    $cart = Cart::factory()->for($this->customer)->for($this->product)->create();

    $this->actingAs($this->customer)
        ->from(route('cart.index'))
        ->delete(route('cart.destroy', $cart))
        ->assertRedirect(route('cart.index'));

    $this->assertDatabaseMissing('carts', ['id' => $cart->id]);
});

it('does not let a user touch another user’s cart', function () {
    $otherCart = Cart::factory()->for(User::factory())->for($this->product)->create();

    $this->actingAs($this->customer);

    $this->patch(route('cart.update', $otherCart), ['quantity' => 5])->assertForbidden();
    $this->delete(route('cart.destroy', $otherCart))->assertForbidden();

    $this->assertDatabaseHas('carts', ['id' => $otherCart->id]);
});

it('shows an empty state when the cart has nothing in it', function () {
    $this->actingAs($this->customer)
        ->get(route('cart.index'))
        ->assertSuccessful()
        ->assertSee('Your cart is empty');
});
