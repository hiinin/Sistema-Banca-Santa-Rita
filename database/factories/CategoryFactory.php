<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nome = fake()->unique()->words(2, true);

        return [
            'nome' => ucfirst($nome),
            'slug' => Str::slug($nome).'-'.fake()->unique()->numberBetween(1, 999),
            'descricao' => fake()->sentence(),
            'status' => 'ativo',
            'ordem' => fake()->numberBetween(1, 10),
        ];
    }
}
