<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateContentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $contentId = $this->route('content')?->id ?? $this->route('conteudo');

        return [
            'titulo' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('contents', 'slug')->ignore($contentId)],
            'descricao' => ['nullable', 'string'],
            'tipo' => ['required', 'in:foto,video'],
            'categoria_id' => ['required', 'exists:categories,id'],
            'imagem' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'video_url' => ['nullable', 'url', 'max:500'],
            'video_arquivo' => ['nullable', 'file', 'mimes:mp4,webm', 'max:51200'],
            'ordem' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:rascunho,publicado'],
            'destaque' => ['required', 'in:sim,não'],
            'data_publicacao' => ['nullable', 'date'],
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'titulo.required' => 'O título do conteúdo é obrigatório.',
            'slug.unique' => 'Este slug já está sendo utilizado.',
            'tipo.required' => 'O tipo do conteúdo (foto ou vídeo) é obrigatório.',
            'tipo.in' => 'O tipo deve ser "foto" ou "vídeo".',
            'categoria_id.required' => 'A categoria é obrigatória.',
            'categoria_id.exists' => 'A categoria selecionada é inválida.',
            'imagem.image' => 'O arquivo enviado deve ser uma imagem válida.',
            'imagem.mimes' => 'A imagem deve estar no formato JPG, JPEG, PNG ou WEBP.',
            'imagem.max' => 'A imagem não pode ultrapassar 10MB.',
            'video_url.url' => 'A URL do vídeo deve ser um link válido.',
            'video_arquivo.mimes' => 'O arquivo de vídeo deve estar no formato MP4 ou WEBM.',
            'video_arquivo.max' => 'O vídeo enviado não pode ultrapassar 50MB.',
            'ordem.required' => 'A ordem de exibição é obrigatória.',
            'status.required' => 'O status é obrigatório.',
            'destaque.required' => 'Informe se o conteúdo deve ser destaque.',
        ];
    }
}
