<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Models\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Show every line in the signed in user's cart.
     */
    public function index(Request $request): View
    {
        return view('cart.index', [
            'cartItems' => $request->user()->cartItems()->with('product')->latest()->get(),
        ]);
    }

    /**
     * Add a product to the cart, merging with any existing line for it.
     */
    public function store(StoreCartItemRequest $request): RedirectResponse
    {
        $cartItem = $request->user()->cartItems()->firstOrNew([
            'product_id' => $request->integer('product_id'),
        ]);

        $cartItem->quantity = ($cartItem->exists ? $cartItem->quantity : 0) + $request->integer('quantity');
        $cartItem->save();

        return back()->with('success', 'Product added to your cart.');
    }

    /**
     * Change the quantity of a single cart line.
     */
    public function update(UpdateCartItemRequest $request, Cart $cart): RedirectResponse
    {
        $cart->update($request->validated());

        return back()->with('success', 'Cart updated.');
    }

    /**
     * Remove a line from the cart.
     */
    public function destroy(Cart $cart): RedirectResponse
    {
        Gate::authorize('delete', $cart);

        $cart->delete();

        return back()->with('success', 'Product removed from your cart.');
    }
}
