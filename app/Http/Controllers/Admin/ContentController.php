<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreContentRequest;
use App\Http\Requests\Admin\UpdateContentRequest;
use App\Models\Category;
use App\Models\Content;
use App\Services\MediaUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ContentController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected MediaUploadService $mediaService
    ) {}

    /**
     * Display a listing of the contents.
     */
    public function index(Request $request): View
    {
        $query = Content::with('category');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                    ->orWhere('descricao', 'like', "%{$search}%");
            });
        }

        if ($categoriaId = $request->input('categoria_id')) {
            $query->where('categoria_id', $categoriaId);
        }

        if ($tipo = $request->input('tipo')) {
            $query->where('tipo', $tipo);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($destaque = $request->input('destaque')) {
            $query->where('destaque', $destaque);
        }

        $contents = $query->orderBy('ordem', 'asc')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $categories = Category::active()->orderBy('nome')->get();

        return view('admin.contents.index', compact('contents', 'categories'));
    }

    /**
     * Show the form for creating a new content.
     */
    public function create(): View
    {
        $categories = Category::active()->orderBy('nome')->get();
        $nextOrder = (Content::max('ordem') ?? 0) + 1;

        return view('admin.contents.create', compact('categories', 'nextOrder'));
    }

    /**
     * Store a newly created content in storage.
     */
    public function store(StoreContentRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $baseSlug = Str::slug($data['titulo']);
            $slug = $baseSlug;
            $counter = 1;
            while (Content::where('slug', $slug)->exists()) {
                $slug = "{$baseSlug}-{$counter}";
                $counter++;
            }
            $data['slug'] = $slug;
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        if ($request->hasFile('imagem')) {
            $data['imagem'] = $this->mediaService->uploadImage($request->file('imagem'), 'contents');
        }

        if ($request->hasFile('video_arquivo')) {
            $data['video_arquivo'] = $this->mediaService->uploadVideo($request->file('video_arquivo'), 'videos');
        }

        if (empty($data['data_publicacao'])) {
            $data['data_publicacao'] = now();
        }

        Content::create($data);

        return redirect()->route('admin.contents.index')
            ->with('success', 'Conteúdo cadastrado com sucesso!');
    }

    /**
     * Show the form for editing the specified content.
     */
    public function edit(Content $content): View
    {
        $categories = Category::active()->orderBy('nome')->get();

        return view('admin.contents.edit', compact('content', 'categories'));
    }

    /**
     * Update the specified content in storage.
     */
    public function update(UpdateContentRequest $request, Content $content): RedirectResponse
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['titulo']);
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        if ($request->hasFile('imagem')) {
            $this->mediaService->delete($content->imagem);
            $data['imagem'] = $this->mediaService->uploadImage($request->file('imagem'), 'contents');
        }

        if ($request->hasFile('video_arquivo')) {
            $this->mediaService->delete($content->video_arquivo);
            $data['video_arquivo'] = $this->mediaService->uploadVideo($request->file('video_arquivo'), 'videos');
        }

        $content->update($data);

        return redirect()->route('admin.contents.index')
            ->with('success', 'Conteúdo atualizado com sucesso!');
    }

    /**
     * Remove the specified content from storage.
     */
    public function destroy(Content $content): RedirectResponse
    {
        $this->mediaService->delete($content->imagem);
        $this->mediaService->delete($content->video_arquivo);

        $content->delete();

        return redirect()->route('admin.contents.index')
            ->with('success', 'Conteúdo removido com sucesso!');
    }
}
