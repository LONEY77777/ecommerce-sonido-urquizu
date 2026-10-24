<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Categories carried by the store.
     *
     * @var list<string>
     */
    public const CATEGORIES = [
        'Writing',
        'Paper',
        'Art Supplies',
        'Desk Accessories',
        'Filing & Storage',
        'School Supplies',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(3, true),
            'category' => fake()->randomElement(self::CATEGORIES),
            'price' => fake()->randomFloat(2, 50, 8000),
            'description' => fake()->paragraphs(2, true),
            'stock' => fake()->numberBetween(0, 200),
            'image' => null,
        ];
    }

    /**
     * Indicate that the product is sold out.
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => 0,
        ]);
    }

    /**
     * Indicate that the product has plenty of stock.
     */
    public function inStock(int $stock = 100): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => $stock,
        ]);
    }
}
