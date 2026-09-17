<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
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
        $categoryId = $this->route('category')?->id ?? $this->route('categoria');

        return [
            'nome' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('categories', 'slug')->ignore($categoryId)],
            'descricao' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'in:ativo,inativo'],
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
            'nome.required' => 'O nome da categoria é obrigatório.',
            'slug.unique' => 'Este slug já está sendo utilizado por outra categoria.',
            'status.required' => 'O status da categoria é obrigatório.',
            'status.in' => 'O status selecionado é inválido.',
            'ordem.required' => 'A ordem de exibição é obrigatória.',
            'ordem.integer' => 'A ordem deve ser um número inteiro.',
        ];
    }
}
