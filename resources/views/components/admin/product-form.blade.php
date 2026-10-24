@props(['action', 'method' => 'POST', 'product' => null, 'categories' => [], 'submitLabel' => 'Save product'])

<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="grid gap-6 lg:grid-cols-3">
    @csrf

    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="lg:col-span-2">
        <div class="rounded-xl border border-slate-200 bg-white p-6">
            <h2 class="text-base font-semibold text-slate-900">Product details</h2>

            <div class="mt-6 grid gap-6 sm:grid-cols-2">
                <x-form.field label="Product name" name="name" :required="true" class="sm:col-span-2">
                    <x-form.input name="name" :value="$product?->name" required />
                </x-form.field>

                <x-form.field label="Category" name="category" :required="true"
                              hint="Pick an existing category or type a new one.">
                    <x-form.input name="category" :value="$product?->category" list="category-options" required />
                    <datalist id="category-options">
                        @foreach ($categories as $category)
                            <option value="{{ $category }}"></option>
                        @endforeach
                    </datalist>
                </x-form.field>

                <x-form.field label="Price" name="price" :required="true">
                    <div class="relative">
                        <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-slate-500">
                            {{ config('store.currency_symbol') }}
                        </span>
                        <x-form.input name="price" type="number" step="0.01" min="0"
                                      :value="$product?->price" class="pl-7" required />
                    </div>
                </x-form.field>

                <x-form.field label="Stock quantity" name="stock" :required="true">
                    <x-form.input name="stock" type="number" min="0" step="1" :value="$product?->stock ?? 0" required />
                </x-form.field>

                <x-form.field label="Description" name="description" :required="true" class="sm:col-span-2">
                    <x-form.textarea name="description" rows="6" :value="$product?->description" required />
                </x-form.field>
            </div>
        </div>
    </div>

    <aside class="lg:col-span-1">
        <div class="rounded-xl border border-slate-200 bg-white p-6">
            <h2 class="text-base font-semibold text-slate-900">Product image</h2>

            <div class="mt-5 overflow-hidden rounded-lg border border-slate-200 bg-slate-50">
                <img src="{{ $product?->imageUrl() ?? asset('images/product-placeholder.svg') }}"
                     alt="{{ $product ? 'Current image for '.$product->name : 'Placeholder image' }}"
                     class="aspect-square w-full object-cover">
            </div>

            <div class="mt-5">
                <x-form.field label="Upload a new image" name="image"
                              hint="JPG, PNG or WebP up to 2 MB. Leave empty to keep the current image.">
                    <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/webp"
                           class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-brand-50 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-brand-700 hover:file:bg-brand-100">
                </x-form.field>
            </div>
        </div>

        <div class="mt-6 flex flex-wrap gap-3">
            <button type="submit"
                    class="flex-1 rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700">
                {{ $submitLabel }}
            </button>

            <a href="{{ route('admin.products.index') }}"
               class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                Cancel
            </a>
        </div>
    </aside>
</form>
