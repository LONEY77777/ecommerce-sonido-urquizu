<?php

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
});

/**
 * A valid set of product attributes for the admin form.
 *
 * @return array<string, mixed>
 */
function productAttributes(array $overrides = []): array
{
    return array_merge([
        'name' => 'Rhodia Dot Pad No. 16',
        'category' => 'Paper',
        'price' => 9.75,
        'description' => 'Eighty sheets of famously smooth 80g paper with a 5mm dot grid.',
        'stock' => 120,
    ], $overrides);
}

it('lists products with pagination', function () {
    Product::factory()->count(12)->create();

    $this->actingAs($this->admin)
        ->get(route('admin.products.index'))
        ->assertSuccessful()
        ->assertViewHas('products', fn ($products) => $products->count() === 10 && $products->total() === 12);
});

it('searches products by name and category', function () {
    Product::factory()->create(['name' => 'Moleskine Notebook', 'category' => 'Paper']);
    Product::factory()->create(['name' => 'Bamboo Organiser', 'category' => 'Desk Accessories']);

    $this->actingAs($this->admin);

    $this->get(route('admin.products.index', ['search' => 'Moleskine']))
        ->assertSee('Moleskine Notebook')
        ->assertDontSee('Bamboo Organiser');

    $this->get(route('admin.products.index', ['search' => 'Desk Accessories']))
        ->assertSee('Bamboo Organiser')
        ->assertDontSee('Moleskine Notebook');
});

it('creates a product', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.products.store'), productAttributes())
        ->assertRedirect(route('admin.products.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('products', [
        'name' => 'Rhodia Dot Pad No. 16',
        'category' => 'Paper',
        'price' => '9.75',
        'stock' => 120,
    ]);
});

it('stores an uploaded product image under a generated name', function () {
    Storage::fake('public');

    $this->actingAs($this->admin)
        ->post(route('admin.products.store'), productAttributes([
            'image' => fakeImageUpload('rhodia.png'),
        ]));

    $product = Product::sole();

    expect($product->image)->toStartWith('products/')
        ->and($product->image)->not->toContain('rhodia.png');

    Storage::disk('public')->assertExists($product->image);
});

it('rejects an invalid product', function (array $overrides, string $field) {
    $this->actingAs($this->admin)
        ->post(route('admin.products.store'), productAttributes($overrides))
        ->assertInvalid($field);

    $this->assertDatabaseCount('products', 0);
})->with([
    'missing name' => [['name' => ''], 'name'],
    'missing category' => [['category' => ''], 'category'],
    'negative price' => [['price' => -1], 'price'],
    'non numeric price' => [['price' => 'free'], 'price'],
    'negative stock' => [['stock' => -5], 'stock'],
    'missing description' => [['description' => ''], 'description'],
]);

it('rejects a non-image upload', function () {
    Storage::fake('public');

    $this->actingAs($this->admin)
        ->post(route('admin.products.store'), productAttributes([
            'image' => UploadedFile::fake()->create('malware.php', 100, 'application/x-php'),
        ]))
        ->assertInvalid('image');
});

it('rejects an image larger than 2MB', function () {
    Storage::fake('public');

    $this->actingAs($this->admin)
        ->post(route('admin.products.store'), productAttributes([
            'image' => fakeImageUpload('huge.png', 3000),
        ]))
        ->assertInvalid('image');
});

it('updates a product', function () {
    $product = Product::factory()->create(['name' => 'Old Name', 'stock' => 5]);

    $this->actingAs($this->admin)
        ->put(route('admin.products.update', $product), productAttributes([
            'name' => 'New Name',
            'stock' => 50,
        ]))
        ->assertRedirect(route('admin.products.index'))
        ->assertSessionHas('success');

    expect($product->refresh()->name)->toBe('New Name')
        ->and($product->stock)->toBe(50);
});

it('keeps the existing image when no new one is uploaded', function () {
    Storage::fake('public');

    $product = Product::factory()->create(['image' => 'products/original.jpg']);

    $this->actingAs($this->admin)
        ->put(route('admin.products.update', $product), productAttributes());

    expect($product->refresh()->image)->toBe('products/original.jpg');
});

it('replaces and deletes the old image when a new one is uploaded', function () {
    Storage::fake('public');
    Storage::disk('public')->put('products/original.jpg', 'contents');

    $product = Product::factory()->create(['image' => 'products/original.jpg']);

    $this->actingAs($this->admin)
        ->put(route('admin.products.update', $product), productAttributes([
            'image' => fakeImageUpload('replacement.png'),
        ]));

    expect($product->refresh()->image)->not->toBe('products/original.jpg');

    Storage::disk('public')->assertMissing('products/original.jpg');
    Storage::disk('public')->assertExists($product->image);
});

it('deletes a product and its stored image', function () {
    Storage::fake('public');
    Storage::disk('public')->put('products/doomed.jpg', 'contents');

    $product = Product::factory()->create(['image' => 'products/doomed.jpg']);

    $this->actingAs($this->admin)
        ->delete(route('admin.products.destroy', $product))
        ->assertRedirect(route('admin.products.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseMissing('products', ['id' => $product->id]);
    Storage::disk('public')->assertMissing('products/doomed.jpg');
});

it('refuses to delete a product that appears on an order', function () {
    $product = Product::factory()->create();
    Order::factory()->create()->items()->create([
        'product_id' => $product->id,
        'product_name' => $product->name,
        'quantity' => 1,
        'price' => $product->price,
    ]);

    $this->actingAs($this->admin)
        ->from(route('admin.products.index'))
        ->delete(route('admin.products.destroy', $product))
        ->assertRedirect(route('admin.products.index'))
        ->assertSessionHas('error');

    $this->assertDatabaseHas('products', ['id' => $product->id]);
});

it('shows the create and edit forms', function () {
    $product = Product::factory()->create();

    $this->actingAs($this->admin);

    $this->get(route('admin.products.create'))->assertSuccessful();
    $this->get(route('admin.products.edit', $product))->assertSuccessful()->assertSee($product->name);
    $this->get(route('admin.products.show', $product))->assertSuccessful()->assertSee($product->name);
});
