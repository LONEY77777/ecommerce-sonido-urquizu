<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show headline figures plus the data behind the dashboard charts.
     */
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'totalProducts' => Product::count(),
            'totalOrders' => Order::count(),
            'totalUsers' => User::where('role', UserRole::User)->count(),
            'totalRevenue' => (float) Order::revenueGenerating()->sum('total_amount'),
            'ordersByStatus' => $this->ordersByStatus(),
            'revenueByDay' => $this->revenueByDay(),
            'lowStockProducts' => Product::where('stock', '<', 10)->orderBy('stock')->limit(5)->get(),
            'recentOrders' => Order::with('user:id,name')->latest()->limit(5)->get(),
        ]);
    }

    /**
     * Order counts keyed by status, including statuses with no orders yet.
     *
     * @return array<string, int>
     */
    private function ordersByStatus(): array
    {
        $counts = Order::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return collect(OrderStatus::cases())
            ->mapWithKeys(fn (OrderStatus $status) => [
                $status->label() => (int) ($counts[$status->value] ?? 0),
            ])
            ->all();
    }

    /**
     * Completed revenue for each of the last 14 days, zero-filled.
     *
     * @return array<string, float>
     */
    private function revenueByDay(): array
    {
        $start = now()->subDays(13)->startOfDay();

        $totals = Order::revenueGenerating()
            ->where('created_at', '>=', $start)
            ->selectRaw('DATE(created_at) as day, SUM(total_amount) as total')
            ->groupBy('day')
            ->pluck('total', 'day');

        $series = [];

        for ($date = $start->copy(); $date->lte(now()); $date->addDay()) {
            $key = $date->toDateString();
            $series[$date->format('M j')] = (float) ($totals[$key] ?? 0);
        }

        return $series;
    }
}
