<x-layouts.app :title="'Order '.$order->order_number">
    <div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
        <a href="{{ route('orders.index') }}" class="text-sm font-semibold text-brand-700 hover:text-brand-800">
            <span aria-hidden="true">&larr;</span> Back to my orders
        </a>

        <header class="mt-6 flex flex-wrap items-start justify-between gap-4 border-b border-slate-200 pb-6">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900">{{ $order->order_number }}</h1>
                <p class="mt-1.5 text-sm text-slate-600">
                    Placed {{ $order->created_at->format('j F Y') }} at {{ $order->created_at->format('g:ia') }}
                </p>
            </div>

            <x-status-badge :status="$order->status" class="text-sm" />
        </header>

        <div class="mt-8 grid gap-6 sm:grid-cols-2">
            <div class="rounded-xl border border-slate-200 bg-white p-6">
                <h2 class="text-sm font-semibold text-slate-900">Delivery details</h2>
                <dl class="mt-4 space-y-3 text-sm">
                    <div>
                        <dt class="text-slate-500">Name</dt>
                        <dd class="mt-0.5 font-medium text-slate-900">{{ $order->customer_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500">Phone</dt>
                        <dd class="mt-0.5 font-medium text-slate-900">{{ $order->phone }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500">Address</dt>
                        <dd class="mt-0.5 whitespace-pre-line font-medium text-slate-900">{{ $order->address }}</dd>
                    </div>
                    @if ($order->notes)
                        <div>
                            <dt class="text-slate-500">Notes</dt>
                            <dd class="mt-0.5 whitespace-pre-line text-slate-700">{{ $order->notes }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-6">
                <h2 class="text-sm font-semibold text-slate-900">Order status</h2>

                <ol class="mt-5 space-y-4">
                    @foreach ([\App\Enums\OrderStatus::Pending, \App\Enums\OrderStatus::Processing, \App\Enums\OrderStatus::Completed] as $index => $step)
                        @php
                            $isCancelled = $order->status === \App\Enums\OrderStatus::Cancelled;
                            $currentIndex = match ($order->status) {
                                \App\Enums\OrderStatus::Pending => 0,
                                \App\Enums\OrderStatus::Processing => 1,
                                \App\Enums\OrderStatus::Completed => 2,
                                \App\Enums\OrderStatus::Cancelled => -1,
                            };
                            $reached = ! $isCancelled && $index <= $currentIndex;
                        @endphp
                        <li class="flex items-center gap-3">
                            <span class="flex size-6 shrink-0 items-center justify-center rounded-full {{ $reached ? 'bg-brand-600 text-white' : 'bg-slate-100 text-slate-400' }}">
                                @if ($reached)
                                    <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M5 12.5l5 5L19 7" />
                                    </svg>
                                @else
                                    <span class="size-1.5 rounded-full bg-current" aria-hidden="true"></span>
                                @endif
                            </span>
                            <span class="text-sm {{ $reached ? 'font-semibold text-slate-900' : 'text-slate-500' }}">
                                {{ $step->label() }}
                            </span>
                        </li>
                    @endforeach

                    @if ($order->status === \App\Enums\OrderStatus::Cancelled)
                        <li class="flex items-center gap-3">
                            <span class="flex size-6 shrink-0 items-center justify-center rounded-full bg-rose-100 text-rose-700">
                                <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" aria-hidden="true">
                                    <path d="M6 6l12 12M18 6L6 18" />
                                </svg>
                            </span>
                            <span class="text-sm font-semibold text-rose-700">Cancelled</span>
                        </li>
                    @endif
                </ol>
            </div>
        </div>

        <div class="mt-6 overflow-hidden rounded-xl border border-slate-200 bg-white">
            <h2 class="border-b border-slate-200 px-6 py-4 text-sm font-semibold text-slate-900">Items</h2>

            <ul role="list" class="divide-y divide-slate-100">
                @foreach ($order->items as $item)
                    <li class="flex items-start gap-4 px-6 py-4">
                        <img src="{{ $item->product?->imageUrl() ?? asset('images/product-placeholder.svg') }}" alt=""
                             aria-hidden="true" class="size-14 shrink-0 rounded-lg border border-slate-200 object-cover">

                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-slate-900">{{ $item->product_name }}</p>
                            <p class="mt-0.5 text-sm text-slate-500">
                                <x-price :amount="$item->price" /> &times; {{ $item->quantity }}
                            </p>
                        </div>

                        <p class="shrink-0 text-sm font-semibold text-slate-900">
                            <x-price :amount="$item->subtotal()" />
                        </p>
                    </li>
                @endforeach
            </ul>

            <div class="flex items-baseline justify-between border-t border-slate-200 bg-slate-50 px-6 py-4">
                <span class="text-base font-semibold text-slate-900">Total</span>
                <span class="text-xl font-bold text-slate-900"><x-price :amount="$order->total_amount" /></span>
            </div>
        </div>
    </div>
</x-layouts.app>
