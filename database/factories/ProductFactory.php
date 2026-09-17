<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nome = fake()->words(3, true);

        return [
            'nome' => ucfirst($nome),
            'slug' => Str::slug($nome).'-'.fake()->unique()->numberBetween(1, 9999),
            'descricao' => fake()->paragraph(),
            'imagem' => null,
            'preco' => fake()->randomFloat(2, 5, 80),
            'categoria_id' => Category::factory(),
            'destaque' => fake()->randomElement(['sim', 'não']),
            'status' => 'publicado',
            'ordem' => fake()->numberBetween(1, 20),
        ];
    }
}
