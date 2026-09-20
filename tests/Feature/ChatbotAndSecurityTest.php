<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Configuration;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatbotAndSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Configuration::current();
    }

    public function test_security_headers_are_present_in_responses(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    public function test_seo_meta_tags_and_schema_are_rendered(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('<meta property="og:site_name"', false);
        $response->assertSee('<meta name="twitter:card"', false);
        $response->assertSee('application/ld+json', false);
        $response->assertSee('"@type": "Newsstand"', false);
        $response->assertSee('"@type": "WebSite"', false);
    }

    public function test_chatbot_greeting_response(): void
    {
        $response = $this->postJson(route('chatbot.message'), [
            'message' => 'Olá, bom dia!',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['reply', 'suggestions']);
        $this->assertStringContainsString('Rita', (string) $response->json('reply'));
    }

    public function test_chatbot_hours_inquiry(): void
    {
        $response = $this->postJson(route('chatbot.message'), [
            'message' => 'Qual o horário de funcionamento da banca?',
        ]);

        $response->assertStatus(200);
        $this->assertStringContainsString('Horário de Funcionamento', (string) $response->json('reply'));
    }

    public function test_chatbot_location_inquiry(): void
    {
        $response = $this->postJson(route('chatbot.message'), [
            'message' => 'Onde fica a banca?',
        ]);

        $response->assertStatus(200);
        $this->assertStringContainsString('localizados', (string) $response->json('reply'));
    }

    public function test_chatbot_product_search(): void
    {
        $category = Category::factory()->create(['nome' => 'Quadrinhos', 'status' => 'ativo']);
        Product::factory()->create([
            'nome' => 'Gibi Turma da Mônica Edição Especial',
            'categoria_id' => $category->id,
            'status' => 'publicado',
            'preco' => 15.00,
        ]);

        $response = $this->postJson(route('chatbot.message'), [
            'message' => 'gibi monica',
        ]);

        $response->assertStatus(200);
        $this->assertNotEmpty($response->json('items'));
        $this->assertEquals('Gibi Turma da Mônica Edição Especial', $response->json('items.0.nome'));
    }

    public function test_chatbot_requires_valid_message(): void
    {
        $response = $this->postJson(route('chatbot.message'), [
            'message' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['message']);
    }

    public function test_sitemap_xml_endpoint(): void
    {
        $response = $this->get(route('sitemap'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml');
        $response->assertSee('<urlset', false);
    }
}
