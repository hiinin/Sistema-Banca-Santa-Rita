<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\MediaUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected MediaUploadService $mediaService
    ) {}

    /**
     * Display a listing of the exhibition items.
     */
    public function index(Request $request): View
    {
        $query = Product::with('category');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                    ->orWhere('descricao', 'like', "%{$search}%");
            });
        }

        if ($categoriaId = $request->input('categoria_id')) {
            $query->where('categoria_id', $categoriaId);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($destaque = $request->input('destaque')) {
            $query->where('destaque', $destaque);
        }

        $products = $query->orderBy('ordem', 'asc')
            ->orderBy('nome', 'asc')
            ->paginate(10)
            ->withQueryString();

        $categories = Category::active()->orderBy('nome')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new exhibition item.
     */
    public function create(): View
    {
        $categories = Category::active()->orderBy('nome')->get();
        $nextOrder = (Product::max('ordem') ?? 0) + 1;

        return view('admin.products.create', compact('categories', 'nextOrder'));
    }

    /**
     * Store a newly created exhibition item in storage.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $baseSlug = Str::slug($data['nome']);
            $slug = $baseSlug;
            $counter = 1;
            while (Product::where('slug', $slug)->exists()) {
                $slug = "{$baseSlug}-{$counter}";
                $counter++;
            }
            $data['slug'] = $slug;
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        if ($request->hasFile('imagem')) {
            $data['imagem'] = $this->mediaService->uploadImage($request->file('imagem'), 'products');
        }

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Item em exposição cadastrado com sucesso!');
    }

    /**
     * Show the form for editing the specified exhibition item.
     */
    public function edit(Product $product): View
    {
        $categories = Category::active()->orderBy('nome')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified exhibition item in storage.
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['nome']);
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        if ($request->hasFile('imagem')) {
            $this->mediaService->delete($product->imagem);
            $data['imagem'] = $this->mediaService->uploadImage($request->file('imagem'), 'products');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Item atualizado com sucesso!');
    }

    /**
     * Remove the specified exhibition item from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $this->mediaService->delete($product->imagem);
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Item removido do catálogo com sucesso!');
    }
}
