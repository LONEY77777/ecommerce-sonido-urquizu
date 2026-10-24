@props(['product'])

<article {{ $attributes->merge(['class' => 'group flex flex-col overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm transition hover:shadow-md']) }}>
    <a href="{{ route('products.show', $product) }}" class="relative block aspect-square overflow-hidden bg-slate-50">
        <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" loading="lazy"
             class="size-full object-cover transition duration-300 group-hover:scale-105">

        @if ($product->stock < 1)
            <span class="absolute left-3 top-3 rounded-full bg-slate-900/85 px-2.5 py-1 text-xs font-semibold text-white">
                Out of stock
            </span>
        @elseif ($product->stock < 10)
            <span class="absolute left-3 top-3 rounded-full bg-amber-500 px-2.5 py-1 text-xs font-semibold text-white">
                Only {{ $product->stock }} left
            </span>
        @endif
    </a>

    <div class="flex flex-1 flex-col gap-2 p-4">
        <p class="text-xs font-semibold uppercase tracking-wide text-brand-700">{{ $product->category }}</p>

        <h3 class="text-sm font-semibold leading-snug text-slate-900">
            <a href="{{ route('products.show', $product) }}" class="hover:text-brand-700">{{ $product->name }}</a>
        </h3>

        <p class="line-clamp-2 text-sm leading-6 text-slate-600">{{ $product->shortDescription() }}</p>

        <p class="mt-auto pt-2 text-lg font-bold text-slate-900">
            <x-price :amount="$product->price" />
        </p>

        <div class="mt-2 flex flex-col gap-2">
            <x-add-to-cart-form :product="$product" />

            <a href="{{ route('products.show', $product) }}"
               class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                View details
            </a>
        </div>
    </div>
</article>
