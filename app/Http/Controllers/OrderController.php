<?php

namespace App\Http\Controllers;

use App\Actions\PlaceOrderAction;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(private readonly PlaceOrderAction $placeOrder) {}

    /**
     * List the signed in user's order history.
     */
    public function index(Request $request): View
    {
        return view('orders.index', [
            'orders' => $request->user()
                ->orders()
                ->withCount('items')
                ->latest()
                ->paginate(10),
        ]);
    }

    /**
     * Show the checkout form with a summary of the cart.
     */
    public function checkout(Request $request): View|RedirectResponse
    {
        $cartItems = $request->user()->cartItems()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        return view('orders.checkout', [
            'cartItems' => $cartItems,
            'total' => $cartItems->sum(fn ($item) => $item->subtotal()),
        ]);
    }

    /**
     * Convert the cart into a confirmed order.
     */
    public function store(StoreOrderRequest $request): RedirectResponse
    {
        $order = $this->placeOrder->handle($request->user(), $request->validated());

        return redirect()
            ->route('orders.show', $order)
            ->with('success', "Order {$order->order_number} placed successfully. Thank you!");
    }

    /**
     * Show a single order with its line items.
     */
    public function show(Order $order): View
    {
        Gate::authorize('view', $order);

        $order->load('items.product');

        return view('orders.show', ['order' => $order]);
    }
}
