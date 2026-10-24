<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Paginated product list, searchable by name or category.
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();

        return view('admin.products.index', [
            'products' => Product::search($search ?: null)->latest()->paginate(10)->withQueryString(),
            'search' => $search,
        ]);
    }

    /**
     * Show the create form.
     */
    public function create(): View
    {
        return view('admin.products.create', [
            'categories' => Product::query()->distinct()->orderBy('category')->pluck('category'),
        ]);
    }

    /**
     * Persist a new product, storing any uploaded image.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $attributes = $request->safe()->except('image');

        if ($request->hasFile('image')) {
            $attributes['image'] = $this->storeImage($request->file('image'));
        }

        Product::create($attributes);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Show a single product in the admin panel.
     */
    public function show(Product $product): View
    {
        return view('admin.products.show', ['product' => $product]);
    }

    /**
     * Show the edit form.
     */
    public function edit(Product $product): View
    {
        return view('admin.products.edit', [
            'product' => $product,
            'categories' => Product::query()->distinct()->orderBy('category')->pluck('category'),
        ]);
    }

    /**
     * Update the product, replacing the image only when a new one is uploaded.
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $attributes = $request->safe()->except('image');

        if ($request->hasFile('image')) {
            $this->deleteImage($product);
            $attributes['image'] = $this->storeImage($request->file('image'));
        }

        $product->update($attributes);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Delete the product along with its stored image.
     */
    public function destroy(Product $product): RedirectResponse
    {
        if ($product->orderItems()->exists()) {
            return back()->with('error', 'This product appears on existing orders and cannot be deleted.');
        }

        $this->deleteImage($product);
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    /**
     * Store an uploaded image under a generated name and return its path.
     */
    private function storeImage(UploadedFile $image): string
    {
        return $image->store('products', 'public');
    }

    /**
     * Remove the product's stored image, if any.
     */
    private function deleteImage(Product $product): void
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
    }
}
