<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContentResource;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ContentApiController extends Controller
{
    /**
     * Display a listing of published contents.
     */
    public function index(Request $request): AnonymousResourceCollection
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

        $contents = $query->ordered()->paginate(12);

        return ContentResource::collection($contents);
    }

    /**
     * Display featured published contents.
     */
    public function featured(): AnonymousResourceCollection
    {
        $contents = Content::published()
            ->featured()
            ->with('category')
            ->ordered()
            ->get();

        return ContentResource::collection($contents);
    }

    /**
     * Display published photos only.
     */
    public function photos(): AnonymousResourceCollection
    {
        $photos = Content::published()
            ->photos()
            ->with('category')
            ->ordered()
            ->paginate(12);

        return ContentResource::collection($photos);
    }

    /**
     * Display published videos only.
     */
    public function videos(): AnonymousResourceCollection
    {
        $videos = Content::published()
            ->videos()
            ->with('category')
            ->ordered()
            ->paginate(12);

        return ContentResource::collection($videos);
    }

    /**
     * Display the specified published content by slug.
     */
    public function show(string $slug): ContentResource
    {
        $content = Content::published()
            ->with('category')
            ->where('slug', $slug)
            ->firstOrFail();

        return new ContentResource($content);
    }
}
