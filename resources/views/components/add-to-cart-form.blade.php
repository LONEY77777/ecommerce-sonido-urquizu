@props(['product', 'withQuantity' => false])

@php
    $outOfStock = $product->stock < 1;
@endphp

@if ($outOfStock)
    <button type="button" disabled
            class="w-full cursor-not-allowed rounded-lg bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-400">
        Out of stock
    </button>
@elseif (auth()->guest())
    {{-- Guests may browse freely, but ordering requires an account. --}}
    <a href="{{ route('login') }}"
       class="flex w-full items-center justify-center gap-2 rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700">
        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <rect x="3" y="11" width="18" height="11" rx="2" /><path d="M7 11V7a5 5 0 0110 0v4" />
        </svg>
        Log in to buy
    </a>
@else
    <form method="POST" action="{{ route('cart.store') }}" class="{{ $withQuantity ? 'flex flex-wrap items-end gap-3' : '' }}">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">

        @if ($withQuantity)
            <div class="w-28">
                <label for="quantity-{{ $product->id }}" class="block text-sm font-medium text-slate-900">Quantity</label>
                <input type="number" name="quantity" id="quantity-{{ $product->id }}"
                       value="1" min="1" max="{{ min(100, $product->stock) }}"
                       class="field-input mt-2">
            </div>
        @endif

        <button type="submit"
                class="flex flex-1 items-center justify-center gap-2 rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600">
            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="9" cy="21" r="1" /><circle cx="20" cy="21" r="1" />
                <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6" />
            </svg>
            Add to cart
        </button>
    </form>
@endif
