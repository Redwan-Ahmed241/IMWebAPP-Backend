<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

/**
 * ProductController — handles product listing & detail.
 *
 * Supports:
 * - ?featured=1      → only featured products
 * - ?category_id=N   → filter by category
 * - ?search=keyword  → search by name
 * - ?limit=N         → limit results
 */
class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Filter: featured products only
        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        // Filter: by category
        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        // Filter: search by name
        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        // Limit
        $limit = $request->input('limit', 20);
        
        // Setup cache key based on the full URL (including all query parameters)
        $cacheKey = 'products_' . md5($request->fullUrl());

        // Cache the database result for 1 hour to protect database limits
        $products = \Illuminate\Support\Facades\Cache::remember($cacheKey, 60 * 60, function () use ($query, $limit) {
            return $query->latest()->take($limit)->get();
        });

        // Return with 'Cache-Control' so the user's browser also caches it for 60 seconds (stops refresh spam)
        return ProductResource::collection($products)
            ->response()
            ->header('Cache-Control', 'public, max-age=60');
    }

    public function show(Product $product)
    {
        $product->load('category');

        return new ProductResource($product);
    }
}
