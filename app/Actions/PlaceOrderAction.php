<?php

namespace App\Actions;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PlaceOrderAction
{
    /**
     * Turn the user's cart into an order, decrementing stock as it goes.
     *
     * Products are locked for the duration of the transaction so two concurrent
     * checkouts cannot both claim the last unit of stock.
     *
     * @param  array{customer_name: string, phone: string, address: string, notes: string|null}  $details
     *
     * @throws ValidationException when stock ran out mid-checkout
     */
    public function handle(User $user, array $details): Order
    {
        return DB::transaction(function () use ($user, $details) {
            $cartItems = $user->cartItems()->with('product')->get();

            if ($cartItems->isEmpty()) {
                throw ValidationException::withMessages([
                    'cart' => 'Your cart is empty.',
                ]);
            }

            $products = Product::whereIn('id', $cartItems->pluck('product_id'))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $totalAmount = 0.0;
            $lines = [];

            foreach ($cartItems as $item) {
                $product = $products[$item->product_id];

                if ($item->quantity > $product->stock) {
                    throw ValidationException::withMessages([
                        'cart' => "{$product->name} only has {$product->stock} unit(s) left. Please update your cart.",
                    ]);
                }

                $totalAmount += (float) $product->price * $item->quantity;

                $lines[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $item->quantity,
                    'price' => $product->price,
                ];
            }

            $order = $user->orders()->create([
                'order_number' => Order::generateOrderNumber(),
                'customer_name' => $details['customer_name'],
                'phone' => $details['phone'],
                'address' => $details['address'],
                'notes' => $details['notes'] ?? null,
                'total_amount' => $totalAmount,
            ]);

            $order->items()->createMany($lines);

            foreach ($lines as $line) {
                $products[$line['product_id']]->decrement('stock', $line['quantity']);
            }

            $user->cartItems()->delete();

            return $order;
        });
    }
}
