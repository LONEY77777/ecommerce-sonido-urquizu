<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'order_number' => Order::generateOrderNumber(),
            'customer_name' => fake()->name(),
            'phone' => fake()->numerify('+8801#########'),
            'address' => fake()->address(),
            'notes' => fake()->boolean(30) ? fake()->sentence() : null,
            'total_amount' => fake()->randomFloat(2, 300, 20000),
            'status' => fake()->randomElement(OrderStatus::cases()),
        ];
    }

    /**
     * Indicate that the order is in the given status.
     */
    public function status(OrderStatus $status): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => $status,
        ]);
    }
}
