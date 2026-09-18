<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'titulo' => $this->titulo,
            'slug' => $this->slug,
            'descricao' => $this->descricao,
            'tipo' => $this->tipo,
            'categoria' => new CategoryResource($this->whenLoaded('category')),
            'imagem_url' => $this->image_url,
            'video_url' => $this->video_url,
            'video_arquivo_url' => $this->video_file_url,
            'video_embed_url' => $this->video_embed_url,
            'ordem' => $this->ordem,
            'status' => $this->status,
            'destaque' => $this->destaque,
            'data_publicacao' => $this->data_publicacao?->toISOString(),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
