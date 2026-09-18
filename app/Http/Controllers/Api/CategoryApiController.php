<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryApiController extends Controller
{
    /**
     * Display a listing of active categories.
     */
    public function index(): AnonymousResourceCollection
    {
        $categories = Category::active()
            ->withCount([
                'contents' => fn ($q) => $q->where('status', 'publicado'),
                'products' => fn ($q) => $q->where('status', 'publicado'),
            ])
            ->ordered()
            ->get();

        return CategoryResource::collection($categories);
    }
}
