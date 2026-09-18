<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Configuration;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContentPublicController extends Controller
{
    /**
     * Display a listing of published contents with filters and pagination.
     */
    public function index(Request $request): View
    {
        $query = Content::published()->with('category');

        if ($tipo = $request->input('tipo')) {
            $query->where('tipo', $tipo);
        }

        if ($categoriaId = $request->input('categoria_id')) {
            $query->where('categoria_id', $categoriaId);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                    ->orWhere('descricao', 'like', "%{$search}%");
            });
        }

        $contents = $query->ordered()
            ->paginate(12)
            ->withQueryString();

        $categories = Category::active()->ordered()->get();
        $config = Configuration::current();

        return view('site.contents.index', compact('contents', 'categories', 'config'));
    }

    /**
     * Display the specified published content.
     */
    public function show(string $slug): View
    {
        $content = Content::published()
            ->with('category')
            ->where('slug', $slug)
            ->firstOrFail();

        $relacionados = Content::published()
            ->where('categoria_id', $content->categoria_id)
            ->where('id', '!=', $content->id)
            ->ordered()
            ->take(3)
            ->get();

        $config = Configuration::current();

        return view('site.contents.show', compact('content', 'relacionados', 'config'));
    }
}
