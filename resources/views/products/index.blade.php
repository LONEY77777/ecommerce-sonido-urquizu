<x-layouts.app title="Products">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <header class="border-b border-slate-200 pb-6">
            <h1 class="text-3xl font-bold tracking-tight text-slate-900">Products</h1>
            <p class="mt-1.5 text-sm text-slate-600">
                {{ $products->total() }} {{ Str::plural('product', $products->total()) }}
                @if ($selectedCategory) in <span class="font-medium text-slate-900">{{ $selectedCategory }}</span> @endif
            </p>
        </header>

        {{-- Filters --}}
        <form method="GET" action="{{ route('products.index') }}" class="mt-6 flex flex-wrap items-end gap-3">
            <div class="min-w-64 flex-1">
                <label for="search" class="sr-only">Search products</label>
                <div class="relative">
                    <svg class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="7" /><path d="M21 21l-4.3-4.3" />
                    </svg>
                    <input type="search" name="search" id="search" value="{{ $search }}"
                           placeholder="Search by product name or category"
                           class="field-input pl-9">
                </div>
            </div>

            <div class="w-56">
                <label for="category" class="sr-only">Category</label>
                <select name="category" id="category" data-auto-submit class="field-input">
                    <option value="">All categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category }}" @selected($selectedCategory === $category)>{{ $category }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700">
                Search
            </button>

            @if ($search || $selectedCategory)
                <a href="{{ route('products.index') }}" class="rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    Clear
                </a>
            @endif
        </form>

        @if ($products->isEmpty())
            <x-empty-state class="mt-10" title="No products found"
                           message="Try a different search term or clear the filters.">
                <a href="{{ route('products.index') }}" class="rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700">
                    Show all products
                </a>
            </x-empty-state>
        @else
            <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>

            <div class="mt-10">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
