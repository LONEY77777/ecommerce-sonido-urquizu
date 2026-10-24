<x-layouts.admin title="Orders" heading="Orders"
                 subheading="{{ $orders->total() }} {{ Str::plural('order', $orders->total()) }} placed.">
    {{-- Filters, in one row above the table --}}
    <form method="GET" action="{{ route('admin.orders.index') }}" class="mb-6 flex flex-wrap items-center gap-3">
        <div class="min-w-64 flex-1">
            <label for="search" class="sr-only">Search orders</label>
            <div class="relative">
                <svg class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                    <circle cx="11" cy="11" r="7" /><path d="M21 21l-4.3-4.3" />
                </svg>
                <input type="search" name="search" id="search" value="{{ $search }}"
                       placeholder="Search by order number, customer or phone" class="field-input pl-9">
            </div>
        </div>

        <div class="w-48">
            <label for="status" class="sr-only">Status</label>
            <select name="status" id="status" data-auto-submit class="field-input">
                <option value="">All statuses</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}" @selected($selectedStatus === $status->value)>
                        {{ $status->label() }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">
            Search
        </button>

        @if ($search || $selectedStatus)
            <a href="{{ route('admin.orders.index') }}" class="rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                Clear
            </a>
        @endif
    </form>

    @if ($orders->isEmpty())
        <x-empty-state title="No orders found"
                       message="{{ $search || $selectedStatus ? 'No order matches those filters.' : 'Orders will appear here as customers place them.' }}" />
    @else
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th scope="col" class="px-6 py-3.5 text-left font-semibold text-slate-900">Order ID</th>
                            <th scope="col" class="px-6 py-3.5 text-left font-semibold text-slate-900">Customer</th>
                            <th scope="col" class="px-6 py-3.5 text-left font-semibold text-slate-900">Phone</th>
                            <th scope="col" class="px-6 py-3.5 text-left font-semibold text-slate-900">Total</th>
                            <th scope="col" class="px-6 py-3.5 text-left font-semibold text-slate-900">Status</th>
                            <th scope="col" class="px-6 py-3.5 text-left font-semibold text-slate-900">Date</th>
                            <th scope="col" class="px-6 py-3.5 text-right font-semibold text-slate-900">
                                <span class="sr-only">View</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($orders as $order)
                            <tr class="hover:bg-slate-50">
                                <td class="whitespace-nowrap px-6 py-4">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="font-medium text-brand-700 hover:text-brand-800">
                                        {{ $order->order_number }}
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-slate-900">{{ $order->customer_name }}</p>

                                    {{-- Only worth showing when the order was placed under a different name. --}}
                                    @if ($order->user->name !== $order->customer_name)
                                        <p class="mt-0.5 text-xs text-slate-500">Account: {{ $order->user->name }}</p>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-slate-600">{{ $order->phone }}</td>
                                <td class="whitespace-nowrap px-6 py-4 font-semibold text-slate-900">
                                    <x-price :amount="$order->total_amount" />
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <x-status-badge :status="$order->status" />
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-slate-600">
                                    {{ $order->created_at->format('j M Y') }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="font-semibold text-brand-700 hover:text-brand-800">
                                        View<span class="sr-only">, order {{ $order->order_number }}</span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif
</x-layouts.admin>
