<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'nome' => $this->nome,
            'slug' => $this->slug,
            'descricao' => $this->descricao,
            'imagem_url' => $this->image_url,
            'preco' => $this->preco,
            'preco_formatado' => $this->formatted_price,
            'categoria' => new CategoryResource($this->whenLoaded('category')),
            'destaque' => $this->destaque,
            'status' => $this->status,
            'ordem' => $this->ordem,
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
