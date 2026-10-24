<x-layouts.app :title="$product->name" :description="$product->shortDescription(160)">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <nav aria-label="Breadcrumb" class="text-sm text-slate-500">
            <ol class="flex flex-wrap items-center gap-2">
                <li><a href="{{ route('home') }}" class="hover:text-brand-700">Home</a></li>
                <li aria-hidden="true">/</li>
                <li><a href="{{ route('products.index') }}" class="hover:text-brand-700">Products</a></li>
                <li aria-hidden="true">/</li>
                <li>
                    <a href="{{ route('products.index', ['category' => $product->category]) }}" class="hover:text-brand-700">
                        {{ $product->category }}
                    </a>
                </li>
            </ol>
        </nav>

        <div class="mt-8 grid gap-10 lg:grid-cols-2">
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}"
                     class="aspect-square w-full object-cover">
            </div>

            <div class="flex flex-col">
                <p class="text-sm font-semibold uppercase tracking-wide text-brand-700">{{ $product->category }}</p>

                <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">{{ $product->name }}</h1>

                <p class="mt-5 text-3xl font-bold text-slate-900">
                    <x-price :amount="$product->price" />
                </p>

                <div class="mt-5">
                    @if ($product->stock > 0)
                        <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-sm font-medium text-emerald-800 ring-1 ring-inset ring-emerald-200">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="12" r="10" /><path d="M8 12.5l2.5 2.5L16 9.5" />
                            </svg>
                            In stock — {{ $product->stock }} available
                        </span>
                    @else
                        <span class="inline-flex items-center gap-2 rounded-full bg-rose-50 px-3 py-1 text-sm font-medium text-rose-800 ring-1 ring-inset ring-rose-200">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                                <circle cx="12" cy="12" r="10" /><path d="M15 9l-6 6M9 9l6 6" />
                            </svg>
                            Out of stock
                        </span>
                    @endif
                </div>

                <div class="mt-8 border-t border-slate-200 pt-8">
                    <h2 class="text-sm font-semibold text-slate-900">Description</h2>
                    <p class="mt-3 whitespace-pre-line text-base leading-7 text-slate-600">{{ $product->description }}</p>
                </div>

                <div class="mt-8 border-t border-slate-200 pt-8">
                    <x-add-to-cart-form :product="$product" :with-quantity="true" />
                </div>
            </div>
        </div>

        @if ($relatedProducts->isNotEmpty())
            <section class="mt-16 border-t border-slate-200 pt-10">
                <h2 class="text-2xl font-bold tracking-tight text-slate-900">More in {{ $product->category }}</h2>

                <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($relatedProducts as $related)
                        <x-product-card :product="$related" />
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</x-layouts.app>
