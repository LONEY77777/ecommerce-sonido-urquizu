<x-layouts.admin :title="$product->name" :heading="$product->name" :subheading="$product->category">
    <x-slot:actions>
        <a href="{{ route('admin.products.edit', $product) }}"
           class="rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700">
            Edit product
        </a>
        <a href="{{ route('admin.products.index') }}"
           class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
            Back to products
        </a>
    </x-slot:actions>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-1">
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" class="aspect-square w-full object-cover">
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="rounded-xl border border-slate-200 bg-white p-6">
                <dl class="grid gap-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm text-slate-500">Price</dt>
                        <dd class="mt-1 text-2xl font-bold text-slate-900"><x-price :amount="$product->price" /></dd>
                    </div>
                    <div>
                        <dt class="text-sm text-slate-500">Stock quantity</dt>
                        <dd class="mt-1 text-2xl font-bold text-slate-900">{{ $product->stock }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-slate-500">Category</dt>
                        <dd class="mt-1 font-medium text-slate-900">{{ $product->category }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-slate-500">Added</dt>
                        <dd class="mt-1 font-medium text-slate-900">{{ $product->created_at->format('j F Y') }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-sm text-slate-500">Description</dt>
                        <dd class="mt-2 whitespace-pre-line leading-7 text-slate-700">{{ $product->description }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</x-layouts.admin>
