<x-layouts.admin title="Dashboard" heading="Dashboard"
                 subheading="How {{ config('store.name') }} is doing right now.">
    {{-- Headline figures --}}
    <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
        <x-stat-card label="Total products" :value="number_format($totalProducts)"
                     caption="{{ $lowStockProducts->count() }} running low">
            <x-slot:icon>
                <path d="M20 7l-8-4-8 4v10l8 4 8-4V7z" /><path d="M4 7l8 4 8-4M12 11v10" />
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card label="Total orders" :value="number_format($totalOrders)"
                     caption="Across all statuses">
            <x-slot:icon>
                <path d="M6 2h12l2 5H4l2-5z" /><path d="M4 7v13h16V7" /><path d="M9 11h6" />
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card label="Total customers" :value="number_format($totalUsers)"
                     caption="Registered accounts">
            <x-slot:icon>
                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" /><circle cx="9" cy="7" r="4" />
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card label="Total revenue"
                     :value="config('store.currency_symbol').number_format($totalRevenue, 2)"
                     caption="From completed orders">
            <x-slot:icon>
                <rect x="2" y="6" width="20" height="12" rx="2" />
                <circle cx="12" cy="12" r="2.5" />
                <path d="M6 12h.01M18 12h.01" />
            </x-slot:icon>
        </x-stat-card>
    </div>

    {{-- Charts --}}
    <div class="mt-6 grid gap-6 xl:grid-cols-3">
        <x-chart.bar class="xl:col-span-2"
                     title="Revenue, last 14 days"
                     subtitle="Completed orders only. Hover a column for the exact figure."
                     :series="$revenueByDay"
                     :prefix="config('store.currency_symbol')"
                     value-label="Revenue"
                     key-label="Day" />

        <x-chart.hbar title="Orders by status"
                      subtitle="Where every order currently sits."
                      :series="$ordersByStatus" />
    </div>

    {{-- Recent orders and low stock --}}
    <div class="mt-6 grid gap-6 xl:grid-cols-3">
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white xl:col-span-2">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                <h2 class="text-base font-semibold text-slate-900">Recent orders</h2>
                <a href="{{ route('admin.orders.index') }}" class="text-sm font-semibold text-brand-700 hover:text-brand-800">
                    View all
                </a>
            </div>

            @if ($recentOrders->isEmpty())
                <p class="px-6 py-12 text-center text-sm text-slate-500">No orders yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left font-semibold text-slate-900">Order</th>
                                <th scope="col" class="px-6 py-3 text-left font-semibold text-slate-900">Customer</th>
                                <th scope="col" class="px-6 py-3 text-left font-semibold text-slate-900">Total</th>
                                <th scope="col" class="px-6 py-3 text-left font-semibold text-slate-900">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($recentOrders as $order)
                                <tr class="hover:bg-slate-50">
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="font-medium text-brand-700 hover:text-brand-800">
                                            {{ $order->order_number }}
                                        </a>
                                        <p class="mt-0.5 text-xs text-slate-500">{{ $order->created_at->diffForHumans() }}</p>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-slate-700">{{ $order->customer_name }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 font-semibold text-slate-900">
                                        <x-price :amount="$order->total_amount" />
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <x-status-badge :status="$order->status" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                <h2 class="text-base font-semibold text-slate-900">Low stock</h2>
                <a href="{{ route('admin.products.index') }}" class="text-sm font-semibold text-brand-700 hover:text-brand-800">
                    Manage
                </a>
            </div>

            @if ($lowStockProducts->isEmpty())
                <p class="px-6 py-12 text-center text-sm text-slate-500">Every product is well stocked.</p>
            @else
                <ul role="list" class="divide-y divide-slate-100">
                    @foreach ($lowStockProducts as $product)
                        <li class="flex items-center gap-3 px-6 py-4">
                            <img src="{{ $product->imageUrl() }}" alt="" aria-hidden="true"
                                 class="size-10 shrink-0 rounded-lg border border-slate-200 object-cover">

                            <div class="min-w-0 flex-1">
                                <a href="{{ route('admin.products.edit', $product) }}"
                                   class="line-clamp-1 text-sm font-medium text-slate-900 hover:text-brand-700">
                                    {{ $product->name }}
                                </a>
                                <p class="mt-0.5 text-xs text-slate-500">{{ $product->category }}</p>
                            </div>

                            <span class="shrink-0 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $product->stock < 1 ? 'bg-rose-100 text-rose-800 ring-rose-600/20' : 'bg-amber-100 text-amber-800 ring-amber-600/20' }}">
                                {{ $product->stock }} left
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-layouts.admin>
