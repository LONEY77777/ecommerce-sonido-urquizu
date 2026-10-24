<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Paginated order list, searchable and filterable by status.
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $status = $request->string('status')->toString();

        $orders = Order::with('user:id,name')
            ->search($search ?: null)
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'statuses' => OrderStatus::cases(),
            'search' => $search,
            'selectedStatus' => $status,
        ]);
    }

    /**
     * Show one order with its customer details and line items.
     */
    public function show(Order $order): View
    {
        $order->load(['user:id,name,email', 'items.product:id,name,image']);

        return view('admin.orders.show', [
            'order' => $order,
            'statuses' => OrderStatus::cases(),
        ]);
    }

    /**
     * Move an order to a new status.
     */
    public function updateStatus(UpdateOrderStatusRequest $request, Order $order): RedirectResponse
    {
        $order->update($request->validated());

        return back()->with('success', "Order {$order->order_number} marked as {$order->status->label()}.");
    }
}
