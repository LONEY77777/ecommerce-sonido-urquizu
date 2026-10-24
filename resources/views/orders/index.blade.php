<x-layouts.app title="My orders">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-slate-900">My orders</h1>
        <p class="mt-1.5 text-sm text-slate-600">Every order you have placed, newest first.</p>

        @if ($orders->isEmpty())
            <x-empty-state class="mt-8" title="No orders yet"
                           message="When you place an order it will show up here with its current status.">
                <a href="{{ route('products.index') }}"
                   class="rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700">
                    Start shopping
                </a>
            </x-empty-state>
        @else
            <div class="mt-8 overflow-hidden rounded-xl border border-slate-200 bg-white">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-3.5 text-left font-semibold text-slate-900">Order</th>
                                <th scope="col" class="px-6 py-3.5 text-left font-semibold text-slate-900">Placed</th>
                                <th scope="col" class="px-6 py-3.5 text-left font-semibold text-slate-900">Items</th>
                                <th scope="col" class="px-6 py-3.5 text-left font-semibold text-slate-900">Total</th>
                                <th scope="col" class="px-6 py-3.5 text-left font-semibold text-slate-900">Status</th>
                                <th scope="col" class="px-6 py-3.5 text-right font-semibold text-slate-900">
                                    <span class="sr-only">View</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($orders as $order)
                                <tr class="hover:bg-slate-50">
                                    <td class="whitespace-nowrap px-6 py-4 font-medium text-slate-900">{{ $order->order_number }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-slate-600">{{ $order->created_at->format('j M Y') }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-slate-600">{{ $order->items_count }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 font-semibold text-slate-900">
                                        <x-price :amount="$order->total_amount" />
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <x-status-badge :status="$order->status" />
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right">
                                        <a href="{{ route('orders.show', $order) }}" class="font-semibold text-brand-700 hover:text-brand-800">
                                            View<span class="sr-only">, order {{ $order->order_number }}</span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
