<x-layouts.app title="Stationery worth writing with">
    {{-- Hero --}}
    <section class="relative overflow-hidden bg-brand-950">
        <div class="absolute inset-0 opacity-20" aria-hidden="true">
            <svg class="size-full" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid" width="32" height="32" patternUnits="userSpaceOnUse">
                        <path d="M32 0H0V32" fill="none" stroke="white" stroke-width="1" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid)" />
            </svg>
        </div>

        <div class="relative mx-auto grid max-w-7xl items-center gap-12 px-4 py-20 sm:px-6 lg:grid-cols-2 lg:px-8 lg:py-28">
            <div>
                <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-brand-100 ring-1 ring-inset ring-white/20">
                    Free delivery on orders over <x-price :amount="config('store.free_delivery_threshold')" :decimals="0" />
                </span>

                <h1 class="mt-6 text-4xl font-bold tracking-tight text-white sm:text-5xl lg:text-6xl">
                    Stationery worth<br class="hidden sm:block"> writing with.
                </h1>

                <p class="mt-6 max-w-lg text-lg leading-8 text-brand-100">
                    Fountain-pen-friendly paper, gel pens that never smudge, artist-grade colour and the desk kit that
                    keeps it all in order. Curated, stocked, and on your desk in two days.
                </p>

                <div class="mt-10 flex flex-wrap gap-4">
                    <a href="{{ route('products.index') }}"
                       class="rounded-lg bg-white px-6 py-3 text-base font-semibold text-brand-900 shadow-sm transition hover:bg-brand-50">
                        Shop now
                    </a>

                    @guest
                        <a href="{{ route('register') }}"
                           class="rounded-lg border border-white/30 px-6 py-3 text-base font-semibold text-white transition hover:bg-white/10">
                            Create an account
                        </a>
                    @endguest
                </div>

                <dl class="mt-12 grid max-w-md grid-cols-3 gap-6 border-t border-white/15 pt-8">
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-brand-200">Products</dt>
                        <dd class="mt-1 text-2xl font-bold text-white">{{ $featuredProducts->isEmpty() ? '—' : '20+' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-brand-200">Categories</dt>
                        <dd class="mt-1 text-2xl font-bold text-white">{{ $categories->count() }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-brand-200">Delivery</dt>
                        <dd class="mt-1 text-2xl font-bold text-white">2 days</dd>
                    </div>
                </dl>
            </div>

            <div class="hidden lg:block">
                <div class="relative mx-auto aspect-4/3 w-full max-w-lg rounded-2xl bg-white/5 p-6 ring-1 ring-inset ring-white/15">
                    <div class="grid h-full grid-cols-2 gap-4">
                        @foreach ($featuredProducts->take(4) as $product)
                            <a href="{{ route('products.show', $product) }}"
                               class="overflow-hidden rounded-xl bg-white/90 transition hover:bg-white">
                                <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}"
                                     class="size-full object-cover">
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Categories --}}
    @if ($categories->isNotEmpty())
        <section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold tracking-tight text-slate-900">Shop by category</h2>

            <div class="mt-6 flex flex-wrap gap-3">
                @foreach ($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category]) }}"
                       class="rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:border-brand-300 hover:bg-brand-50 hover:text-brand-700">
                        {{ $category }}
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Featured products --}}
    <section class="mx-auto max-w-7xl px-4 pb-16 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900">New in store</h2>
                <p class="mt-1.5 text-sm text-slate-600">The latest additions to the shelves.</p>
            </div>

            <a href="{{ route('products.index') }}" class="text-sm font-semibold text-brand-700 hover:text-brand-800">
                Browse all products <span aria-hidden="true">&rarr;</span>
            </a>
        </div>

        @if ($featuredProducts->isEmpty())
            <x-empty-state class="mt-8" title="No products yet"
                           message="The catalogue is empty. Check back shortly." />
        @else
            <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($featuredProducts as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        @endif
    </section>
</x-layouts.app>
