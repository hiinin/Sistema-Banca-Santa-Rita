<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index(Request $request): View
    {
        $query = Category::withCount(['contents', 'products']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                    ->orWhere('descricao', 'like', "%{$search}%");
            });
        }

        $categories = $query->orderBy('ordem', 'asc')
            ->orderBy('nome', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create(): View
    {
        $nextOrder = (Category::max('ordem') ?? 0) + 1;

        return view('admin.categories.create', compact('nextOrder'));
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $baseSlug = Str::slug($data['nome']);
            $slug = $baseSlug;
            $counter = 1;
            while (Category::where('slug', $slug)->exists()) {
                $slug = "{$baseSlug}-{$counter}";
                $counter++;
            }
            $data['slug'] = $slug;
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        Category::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoria cadastrada com sucesso!');
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['nome']);
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoria atualizada com sucesso!');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        $contentsCount = $category->contents()->count();
        $productsCount = $category->products()->count();

        if ($contentsCount > 0 || $productsCount > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', "Não é possível excluir esta categoria porque ela possui {$contentsCount} conteúdo(s) e {$productsCount} item(ns) associado(s). Reatribua-os antes de excluir.");
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoria removida com sucesso!');
    }
}
