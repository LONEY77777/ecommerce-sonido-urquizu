<x-layouts.admin title="Add product" heading="Add product"
                 subheading="Create a new item in the catalogue.">
    <x-admin.product-form :action="route('admin.products.store')"
                          :categories="$categories"
                          submit-label="Create product" />
</x-layouts.admin>
