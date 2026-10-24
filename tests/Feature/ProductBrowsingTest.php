<?php

use App\Models\Product;
use App\Models\User;

it('lets guests browse the homepage and catalogue', function () {
    $product = Product::factory()->inStock()->create(['name' => 'Pilot G2 Gel Pen']);

    $this->get(route('home'))->assertSuccessful()->assertSee('Pilot G2 Gel Pen');
    $this->get(route('products.index'))->assertSuccessful()->assertSee('Pilot G2 Gel Pen');
    $this->get(route('products.show', $product))->assertSuccessful()->assertSee('Pilot G2 Gel Pen');
});

it('shows stock quantity and price on the product page', function () {
    $product = Product::factory()->create(['stock' => 42, 'price' => 19.99]);

    $this->get(route('products.show', $product))
        ->assertSuccessful()
        ->assertSee('42 available')
        ->assertSee('19.99');
});

it('marks sold out products as out of stock', function () {
    $product = Product::factory()->outOfStock()->create();

    $this->get(route('products.show', $product))
        ->assertSuccessful()
        ->assertSee('Out of stock');
});

it('searches products by name', function () {
    Product::factory()->create(['name' => 'Moleskine Notebook', 'category' => 'Paper']);
    Product::factory()->create(['name' => 'Bamboo Organiser', 'category' => 'Desk Accessories']);

    $this->get(route('products.index', ['search' => 'Moleskine']))
        ->assertSuccessful()
        ->assertSee('Moleskine Notebook')
        ->assertDontSee('Bamboo Organiser');
});

it('searches products by category', function () {
    Product::factory()->create(['name' => 'Moleskine Notebook', 'category' => 'Paper']);
    Product::factory()->create(['name' => 'Bamboo Organiser', 'category' => 'Desk Accessories']);

    $this->get(route('products.index', ['search' => 'Desk Accessories']))
        ->assertSuccessful()
        ->assertSee('Bamboo Organiser')
        ->assertDontSee('Moleskine Notebook');
});

it('filters products by category', function () {
    Product::factory()->create(['name' => 'Moleskine Notebook', 'category' => 'Paper']);
    Product::factory()->create(['name' => 'Bamboo Organiser', 'category' => 'Desk Accessories']);

    $this->get(route('products.index', ['category' => 'Paper']))
        ->assertSuccessful()
        ->assertSee('Moleskine Notebook')
        ->assertDontSee('Bamboo Organiser');
});

it('paginates the catalogue', function () {
    Product::factory()->count(15)->create();

    $this->get(route('products.index'))
        ->assertSuccessful()
        ->assertViewHas('products', fn ($products) => $products->count() === 12 && $products->total() === 15);
});

it('prompts guests to log in instead of showing an add to cart button', function () {
    $product = Product::factory()->inStock()->create();

    $this->get(route('products.show', $product))
        ->assertSuccessful()
        ->assertSee('Log in to buy');
});

it('shows an add to cart button to signed in customers', function () {
    $product = Product::factory()->inStock()->create();

    $this->actingAs(User::factory()->create())
        ->get(route('products.show', $product))
        ->assertSuccessful()
        ->assertSee('Add to cart')
        ->assertDontSee('Log in to buy');
});

it('returns 404 for a product that does not exist', function () {
    $this->get('/products/99999')->assertNotFound();
});
