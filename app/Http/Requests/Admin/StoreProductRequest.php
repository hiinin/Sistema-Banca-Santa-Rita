<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('preco') && $this->input('preco') !== null) {
            $preco = $this->input('preco');
            if (is_string($preco)) {
                // Remove R$, espaços e converte formato brasileiro 15,90 para 15.90
                $clean = str_replace(['R$', ' ', '.'], '', $preco);
                $clean = str_replace(',', '.', $clean);
                $this->merge(['preco' => is_numeric($clean) ? (float) $clean : null]);
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:products,slug'],
            'descricao' => ['nullable', 'string'],
            'imagem' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'preco' => ['nullable', 'numeric', 'min:0'],
            'categoria_id' => ['nullable', 'exists:categories,id'],
            'destaque' => ['required', 'in:sim,não'],
            'status' => ['required', 'in:rascunho,publicado'],
            'ordem' => ['required', 'integer', 'min:0'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nome.required' => 'O nome do item em exposição é obrigatório.',
            'slug.unique' => 'Este slug já está sendo utilizado por outro item.',
            'imagem.image' => 'O arquivo enviado deve ser uma imagem válida.',
            'imagem.mimes' => 'A imagem deve estar no formato JPG, JPEG, PNG ou WEBP.',
            'imagem.max' => 'A imagem não pode ultrapassar 10MB.',
            'preco.numeric' => 'O preço informado deve ser um valor numérico válido.',
            'categoria_id.exists' => 'A categoria selecionada é inválida.',
            'destaque.required' => 'Informe se o item deve ser destacado.',
            'status.required' => 'O status de exibição é obrigatório.',
            'ordem.required' => 'A ordem de exibição é obrigatória.',
        ];
    }
}
