<?php

namespace App\Policies;

use App\Models\Cart;
use App\Models\User;

class CartPolicy
{
    /**
     * Determine whether the user can update the cart line.
     */
    public function update(User $user, Cart $cart): bool
    {
        return $cart->user_id === $user->id;
    }

    /**
     * Determine whether the user can remove the cart line.
     */
    public function delete(User $user, Cart $cart): bool
    {
        return $cart->user_id === $user->id;
    }
}
