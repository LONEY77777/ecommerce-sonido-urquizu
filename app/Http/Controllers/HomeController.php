<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Show the storefront landing page with a slice of the catalogue.
     */
    public function __invoke(): View
    {
        return view('home', [
            'featuredProducts' => Product::latest()->limit(8)->get(),
            'categories' => Product::query()->distinct()->orderBy('category')->pluck('category'),
        ]);
    }
}
