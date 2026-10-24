<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    /**
     * Administrators may inspect any order; customers only their own.
     */
    public function view(User $user, Order $order): bool
    {
        return $user->isAdmin() || $order->user_id === $user->id;
    }

    /**
     * Only administrators may move an order through its lifecycle.
     */
    public function updateStatus(User $user, Order $order): bool
    {
        return $user->isAdmin();
    }
}
