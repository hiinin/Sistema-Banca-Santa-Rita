<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Content;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Content>
 */
class ContentFactory extends Factory
{
    protected $model = Content::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $titulo = fake()->sentence(4);

        return [
            'titulo' => $titulo,
            'slug' => Str::slug($titulo).'-'.fake()->unique()->numberBetween(1, 9999),
            'descricao' => fake()->paragraph(),
            'tipo' => fake()->randomElement(['foto', 'video']),
            'categoria_id' => Category::factory(),
            'imagem' => null,
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'video_arquivo' => null,
            'ordem' => fake()->numberBetween(1, 20),
            'status' => 'publicado',
            'destaque' => fake()->randomElement(['sim', 'não']),
            'data_publicacao' => now(),
        ];
    }

    /**
     * Indicate that the content is a photo.
     */
    public function photo(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo' => 'foto',
            'video_url' => null,
            'video_arquivo' => null,
        ]);
    }

    /**
     * Indicate that the content is a video.
     */
    public function video(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo' => 'video',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        ]);
    }
}
