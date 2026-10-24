<x-layouts.admin title="Products" heading="Products"
                 subheading="{{ $products->total() }} {{ Str::plural('product', $products->total()) }} in the catalogue.">
    <x-slot:actions>
        <a href="{{ route('admin.products.create') }}"
           class="inline-flex items-center gap-2 rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700">
            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                <path d="M12 5v14M5 12h14" />
            </svg>
            Add product
        </a>
    </x-slot:actions>

    {{-- Search --}}
    <form method="GET" action="{{ route('admin.products.index') }}" class="mb-6 flex flex-wrap items-center gap-3">
        <div class="min-w-64 flex-1">
            <label for="search" class="sr-only">Search products</label>
            <div class="relative">
                <svg class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                    <circle cx="11" cy="11" r="7" /><path d="M21 21l-4.3-4.3" />
                </svg>
                <input type="search" name="search" id="search" value="{{ $search }}"
                       placeholder="Search by product name or category" class="field-input pl-9">
            </div>
        </div>

        <button type="submit" class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">
            Search
        </button>

        @if ($search)
            <a href="{{ route('admin.products.index') }}" class="rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                Clear
            </a>
        @endif
    </form>

    @if ($products->isEmpty())
        <x-empty-state title="No products found"
                       message="{{ $search ? 'No product matches that search.' : 'Add your first product to get started.' }}">
            <a href="{{ route('admin.products.create') }}"
               class="rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700">
                Add product
            </a>
        </x-empty-state>
    @else
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th scope="col" class="px-6 py-3.5 text-left font-semibold text-slate-900">Product</th>
                            <th scope="col" class="px-6 py-3.5 text-left font-semibold text-slate-900">Category</th>
                            <th scope="col" class="px-6 py-3.5 text-left font-semibold text-slate-900">Price</th>
                            <th scope="col" class="px-6 py-3.5 text-left font-semibold text-slate-900">Stock</th>
                            <th scope="col" class="px-6 py-3.5 text-right font-semibold text-slate-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($products as $product)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $product->imageUrl() }}" alt="" aria-hidden="true"
                                             class="size-11 shrink-0 rounded-lg border border-slate-200 object-cover">
                                        <div class="min-w-0">
                                            <a href="{{ route('admin.products.show', $product) }}"
                                               class="line-clamp-1 font-medium text-slate-900 hover:text-brand-700">
                                                {{ $product->name }}
                                            </a>
                                            <p class="mt-0.5 line-clamp-1 text-xs text-slate-500">{{ $product->shortDescription(60) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-slate-600">{{ $product->category }}</td>
                                <td class="whitespace-nowrap px-6 py-4 font-semibold text-slate-900">
                                    <x-price :amount="$product->price" />
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset
                                        @if ($product->stock < 1) bg-rose-100 text-rose-800 ring-rose-600/20
                                        @elseif ($product->stock < 10) bg-amber-100 text-amber-800 ring-amber-600/20
                                        @else bg-emerald-100 text-emerald-800 ring-emerald-600/20 @endif">
                                        {{ $product->stock }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.products.edit', $product) }}"
                                           class="rounded-lg px-3 py-1.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                                            Edit<span class="sr-only">, {{ $product->name }}</span>
                                        </a>

                                        <x-confirm-delete :id="'delete-product-'.$product->id"
                                                          :action="route('admin.products.destroy', $product)"
                                                          title="Delete this product?"
                                                          :message="'“'.$product->name.'” will be permanently removed from the catalogue.'"
                                                          confirm-label="Delete product" />
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $products->links() }}
        </div>
    @endif
</x-layouts.admin>
