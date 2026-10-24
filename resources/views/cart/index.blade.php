@php
    $total = $cartItems->sum(fn ($item) => $item->subtotal());
@endphp

<x-layouts.app title="Your cart">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-slate-900">Your cart</h1>

        @if ($cartItems->isEmpty())
            <x-empty-state class="mt-8" title="Your cart is empty"
                           message="Browse the catalogue and add a few things you need.">
                <a href="{{ route('products.index') }}"
                   class="rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700">
                    Start shopping
                </a>
            </x-empty-state>
        @else
            <div class="mt-8 grid gap-8 lg:grid-cols-3">
                {{-- Line items --}}
                <div class="lg:col-span-2">
                    <ul role="list" class="divide-y divide-slate-200 overflow-hidden rounded-xl border border-slate-200 bg-white">
                        @foreach ($cartItems as $item)
                            <li class="flex flex-wrap gap-4 p-4 sm:flex-nowrap sm:p-6">
                                <a href="{{ route('products.show', $item->product) }}" class="shrink-0">
                                    <img src="{{ $item->product->imageUrl() }}" alt="{{ $item->product->name }}"
                                         class="size-24 rounded-lg border border-slate-200 object-cover">
                                </a>

                                <div class="flex min-w-0 flex-1 flex-col gap-2">
                                    <div class="flex flex-wrap items-start justify-between gap-2">
                                        <div class="min-w-0">
                                            <h2 class="text-sm font-semibold text-slate-900">
                                                <a href="{{ route('products.show', $item->product) }}" class="hover:text-brand-700">
                                                    {{ $item->product->name }}
                                                </a>
                                            </h2>
                                            <p class="mt-0.5 text-sm text-slate-500">{{ $item->product->category }}</p>
                                            <p class="mt-1 text-sm text-slate-600">
                                                <x-price :amount="$item->product->price" /> each
                                            </p>
                                        </div>

                                        <p class="text-base font-bold text-slate-900">
                                            <x-price :amount="$item->subtotal()" />
                                        </p>
                                    </div>

                                    <div class="mt-auto flex flex-wrap items-center gap-3">
                                        <form method="POST" action="{{ route('cart.update', $item) }}" class="flex items-center gap-2">
                                            @csrf
                                            @method('PATCH')

                                            <label for="quantity-{{ $item->id }}" class="text-sm text-slate-600">Qty</label>
                                            <input type="number" name="quantity" id="quantity-{{ $item->id }}"
                                                   value="{{ $item->quantity }}" min="1" max="{{ min(100, $item->product->stock) }}"
                                                   class="field-input w-20 py-1.5">

                                            <button type="submit"
                                                    class="rounded-lg border border-slate-300 px-3 py-1.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                                                Update
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('cart.destroy', $item) }}">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-sm font-semibold text-rose-700 transition hover:bg-rose-50">
                                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6" />
                                                </svg>
                                                Remove
                                            </button>
                                        </form>

                                        @if ($item->quantity > $item->product->stock)
                                            <p class="text-sm font-medium text-rose-700">
                                                Only {{ $item->product->stock }} left in stock.
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-6">
                        <a href="{{ route('products.index') }}" class="text-sm font-semibold text-brand-700 hover:text-brand-800">
                            <span aria-hidden="true">&larr;</span> Continue shopping
                        </a>
                    </div>
                </div>

                {{-- Summary --}}
                <aside class="lg:col-span-1">
                    <div class="sticky top-24 rounded-xl border border-slate-200 bg-white p-6">
                        <h2 class="text-base font-semibold text-slate-900">Order summary</h2>

                        <dl class="mt-5 space-y-3 text-sm">
                            @foreach ($cartItems as $item)
                                <div class="flex items-start justify-between gap-4">
                                    <dt class="min-w-0 text-slate-600">
                                        <span class="line-clamp-1">{{ $item->product->name }}</span>
                                        <span class="text-slate-400">&times; {{ $item->quantity }}</span>
                                    </dt>
                                    <dd class="shrink-0 font-medium text-slate-900">
                                        <x-price :amount="$item->subtotal()" />
                                    </dd>
                                </div>
                            @endforeach
                        </dl>

                        <div class="mt-5 space-y-3 border-t border-slate-200 pt-5 text-sm">
                            <div class="flex justify-between">
                                <dt class="text-slate-600">Subtotal</dt>
                                <dd class="font-medium text-slate-900"><x-price :amount="$total" /></dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-slate-600">Delivery</dt>
                                <dd class="font-medium text-emerald-700">Free</dd>
                            </div>
                        </div>

                        <div class="mt-5 flex items-baseline justify-between border-t border-slate-200 pt-5">
                            <span class="text-base font-semibold text-slate-900">Total</span>
                            <span class="text-2xl font-bold text-slate-900"><x-price :amount="$total" /></span>
                        </div>

                        <a href="{{ route('checkout') }}"
                           class="mt-6 block w-full rounded-lg bg-brand-600 px-4 py-3 text-center text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700">
                            Proceed to checkout
                        </a>
                    </div>
                </aside>
            </div>
        @endif
    </div>
</x-layouts.app>
