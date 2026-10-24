<x-layouts.admin :title="'Edit '.$product->name" heading="Edit product"
                 :subheading="$product->name">
    <x-slot:actions>
        <a href="{{ route('products.show', $product) }}" target="_blank" rel="noopener"
           class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
            View in store
        </a>
    </x-slot:actions>

    <x-admin.product-form :action="route('admin.products.update', $product)"
                          method="PUT"
                          :product="$product"
                          :categories="$categories"
                          submit-label="Save changes" />
</x-layouts.admin>
