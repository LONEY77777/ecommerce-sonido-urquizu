<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Enums\UserRole;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database with an admin account, a demo customer,
     * the sample catalogue and a handful of orders to populate the dashboard.
     */
    public function run(): void
    {
        $this->call(ProductSeeder::class);

        User::updateOrCreate(
            ['email' => 'admin@stationeryhub.test'],
            [
                'name' => 'Store Administrator',
                'password' => 'password',
                'role' => UserRole::Admin,
                'email_verified_at' => now(),
            ]
        );

        $customer = User::updateOrCreate(
            ['email' => 'customer@stationeryhub.test'],
            [
                'name' => 'Demo Customer',
                'password' => 'password',
                'role' => UserRole::User,
                'email_verified_at' => now(),
            ]
        );

        if ($customer->orders()->doesntExist()) {
            $this->seedSampleOrders($customer);
        }
    }

    /**
     * Create a few orders spread across statuses and recent dates.
     */
    private function seedSampleOrders(User $customer): void
    {
        $products = Product::inStock()->get();

        $statuses = [
            OrderStatus::Completed,
            OrderStatus::Completed,
            OrderStatus::Processing,
            OrderStatus::Pending,
            OrderStatus::Cancelled,
        ];

        foreach ($statuses as $index => $status) {
            $lineProducts = $products->random(min(3, $products->count()));
            $placedAt = now()->subDays(($index * 2) + 1);

            $order = Order::create([
                'user_id' => $customer->id,
                'order_number' => Order::generateOrderNumber(),
                'customer_name' => $customer->name,
                'phone' => '+8801712345678',
                'address' => '42 Elephant Road, Dhaka 1205, Bangladesh',
                'notes' => $index === 0 ? 'Please deliver after 5pm.' : null,
                'total_amount' => 0,
                'status' => $status,
            ]);

            $total = 0.0;

            foreach ($lineProducts as $product) {
                $quantity = random_int(1, 3);
                $total += (float) $product->price * $quantity;

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $quantity,
                    'price' => $product->price,
                ]);
            }

            // Backdate the order so the dashboard charts have a spread of data.
            $order->forceFill([
                'total_amount' => $total,
                'created_at' => $placedAt,
                'updated_at' => $placedAt,
            ])->save();
        }
    }
}
