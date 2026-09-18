<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConfigurationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'nome_banca' => $this->nome_banca,
            'logo_url' => $this->logo_url,
            'favicon_url' => $this->favicon_url,
            'descricao' => $this->descricao,
            'endereco' => $this->endereco,
            'telefone' => $this->telefone,
            'whatsapp' => $this->whatsapp,
            'whatsapp_url' => $this->getWhatsappUrl(),
            'email' => $this->email,
            'instagram' => $this->instagram,
            'facebook' => $this->facebook,
            'horario' => $this->horario,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'texto_sobre' => $this->texto_sobre,
        ];
    }
}
