<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Browse the public catalogue with optional search and category filtering.
     */
    public function index(Request $request): View
    {
        $products = Product::search($request->string('search')->toString() ?: null)
            ->when(
                $request->filled('category'),
                fn ($query) => $query->where('category', $request->string('category')->toString())
            )
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('products.index', [
            'products' => $products,
            'categories' => Product::query()->distinct()->orderBy('category')->pluck('category'),
            'search' => $request->string('search')->toString(),
            'selectedCategory' => $request->string('category')->toString(),
        ]);
    }

    /**
     * Show a single product with a few suggestions from the same category.
     */
    public function show(Product $product): View
    {
        return view('products.show', [
            'product' => $product,
            'relatedProducts' => Product::where('category', $product->category)
                ->whereKeyNot($product->getKey())
                ->latest()
                ->limit(4)
                ->get(),
        ]);
    }
}
