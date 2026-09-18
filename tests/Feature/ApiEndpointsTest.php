<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Content;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiEndpointsTest extends TestCase
{
    use RefreshDatabase;

    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->category = Category::first();
    }

    public function test_api_contents_returns_only_published_items_with_pagination(): void
    {
        // Create a draft content that should never appear in public API
        Content::create([
            'titulo' => 'Conteúdo Rascunho Secreto',
            'slug' => 'conteudo-rascunho-secreto',
            'tipo' => 'foto',
            'categoria_id' => $this->category->id,
            'ordem' => 99,
            'status' => 'rascunho',
            'destaque' => 'não',
        ]);

        $response = $this->getJson('/api/contents');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'titulo',
                        'slug',
                        'tipo',
                        'categoria',
                        'imagem_url',
                        'status',
                        'destaque',
                    ],
                ],
                'links',
                'meta',
            ]);

        $json = $response->json('data');
        $this->assertNotEmpty($json);

        // Ensure draft is not included
        $titles = collect($json)->pluck('titulo');
        $this->assertFalse($titles->contains('Conteúdo Rascunho Secreto'));
    }

    public function test_api_contents_featured_returns_only_featured_published_items(): void
    {
        $response = $this->getJson('/api/contents/featured');

        $response->assertStatus(200);
        $json = $response->json('data');
        $this->assertNotEmpty($json);

        foreach ($json as $item) {
            $this->assertEquals('sim', $item['destaque']);
            $this->assertEquals('publicado', $item['status']);
        }
    }

    public function test_api_contents_photos_returns_photos_only(): void
    {
        $response = $this->getJson('/api/contents/photos');

        $response->assertStatus(200);
        $json = $response->json('data');
        $this->assertNotEmpty($json);

        foreach ($json as $item) {
            $this->assertEquals('foto', $item['tipo']);
        }
    }

    public function test_api_contents_videos_returns_videos_only(): void
    {
        $response = $this->getJson('/api/contents/videos');

        $response->assertStatus(200);
        $json = $response->json('data');
        $this->assertNotEmpty($json);

        foreach ($json as $item) {
            $this->assertEquals('video', $item['tipo']);
        }
    }

    public function test_api_contents_show_by_slug(): void
    {
        $content = Content::published()->first();

        $response = $this->getJson("/api/contents/{$content->slug}");

        $response->assertStatus(200)
            ->assertJsonPath('data.slug', $content->slug)
            ->assertJsonPath('data.titulo', $content->titulo);
    }

    public function test_api_contents_show_draft_slug_returns_404(): void
    {
        $draft = Content::create([
            'titulo' => 'Rascunho Oculto',
            'slug' => 'rascunho-oculto-slug',
            'tipo' => 'foto',
            'categoria_id' => $this->category->id,
            'ordem' => 1,
            'status' => 'rascunho',
            'destaque' => 'não',
        ]);

        $response = $this->getJson("/api/contents/{$draft->slug}");

        $response->assertStatus(404);
    }

    public function test_api_categories_returns_active_categories_with_counts(): void
    {
        $response = $this->getJson('/api/categories');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'nome',
                        'slug',
                        'ordem',
                        'status',
                        'contents_count',
                        'products_count',
                    ],
                ],
            ]);
    }

    public function test_api_products_returns_published_items_only(): void
    {
        // Create draft product
        Product::create([
            'nome' => 'Item Rascunho Privado',
            'slug' => 'item-rascunho-privado',
            'categoria_id' => $this->category->id,
            'ordem' => 1,
            'status' => 'rascunho',
            'destaque' => 'não',
        ]);

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'nome',
                        'slug',
                        'imagem_url',
                        'preco_formatado',
                        'categoria',
                        'status',
                    ],
                ],
            ]);

        $names = collect($response->json('data'))->pluck('nome');
        $this->assertFalse($names->contains('Item Rascunho Privado'));
    }

    public function test_api_products_featured_returns_featured_items_only(): void
    {
        $response = $this->getJson('/api/products/featured');

        $response->assertStatus(200);
        $json = $response->json('data');
        $this->assertNotEmpty($json);

        foreach ($json as $item) {
            $this->assertEquals('sim', $item['destaque']);
            $this->assertEquals('publicado', $item['status']);
        }
    }

    public function test_api_products_show_by_slug(): void
    {
        $product = Product::published()->first();

        $response = $this->getJson("/api/products/{$product->slug}");

        $response->assertStatus(200)
            ->assertJsonPath('data.slug', $product->slug)
            ->assertJsonPath('data.nome', $product->nome);
    }

    public function test_api_configuration_returns_banca_details(): void
    {
        $response = $this->getJson('/api/configuration');

        $response->assertStatus(200)
            ->assertJsonPath('data.nome_banca', 'Banca Santa Rita')
            ->assertJsonStructure([
                'data' => [
                    'nome_banca',
                    'logo_url',
                    'favicon_url',
                    'descricao',
                    'endereco',
                    'whatsapp',
                    'whatsapp_url',
                    'horario',
                    'texto_sobre',
                ],
            ]);
    }
}
