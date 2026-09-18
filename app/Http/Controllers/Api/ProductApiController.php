<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductApiController extends Controller
{
    /**
     * Display a listing of published exhibition items.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Product::published()->with('category');

        if ($categoriaId = $request->input('categoria_id')) {
            $query->where('categoria_id', $categoriaId);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                    ->orWhere('descricao', 'like', "%{$search}%");
            });
        }

        $products = $query->ordered()->paginate(12);

        return ProductResource::collection($products);
    }

    /**
     * Display featured exhibition items.
     */
    public function featured(): AnonymousResourceCollection
    {
        $products = Product::published()
            ->featured()
            ->with('category')
            ->ordered()
            ->get();

        return ProductResource::collection($products);
    }

    /**
     * Display the specified exhibition item by slug.
     */
    public function show(string $slug): ProductResource
    {
        $product = Product::published()
            ->with('category')
            ->where('slug', $slug)
            ->firstOrFail();

        return new ProductResource($product);
    }
}
