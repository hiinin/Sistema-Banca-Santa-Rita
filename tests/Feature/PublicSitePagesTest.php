<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Configuration;
use App\Models\Content;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicSitePagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed initial configuration
        Configuration::current();
    }

    public function test_home_page_can_be_rendered_with_destaques_and_items(): void
    {
        $category = Category::factory()->create(['status' => 'ativo']);

        $content = Content::factory()->create([
            'categoria_id' => $category->id,
            'status' => 'publicado',
            'destaque' => true,
            'titulo' => 'Grande Destaque do Mês',
        ]);

        $product = Product::factory()->create([
            'categoria_id' => $category->id,
            'status' => 'publicado',
            'nome' => 'Revista em Exposição',
            'preco' => 19.90,
        ]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Banca Santa Rita');
        $response->assertSee('Grande Destaque do Mês');
        $response->assertSee('Revista em Exposição');
    }

    public function test_about_page_can_be_rendered(): void
    {
        $response = $this->get(route('site.about'));

        $response->assertStatus(200);
        $response->assertSee('Sobre a Banca Santa Rita');
        $response->assertSee('Nossa História');
    }

    public function test_contents_index_page_can_be_rendered_and_filtered(): void
    {
        $category = Category::factory()->create(['nome' => 'Quadrinhos']);

        $foto = Content::factory()->create([
            'categoria_id' => $category->id,
            'tipo' => 'foto',
            'status' => 'publicado',
            'titulo' => 'Foto Edição Especial',
        ]);

        $video = Content::factory()->create([
            'categoria_id' => $category->id,
            'tipo' => 'video',
            'status' => 'publicado',
            'titulo' => 'Vídeo Unboxing Lançamento',
        ]);

        // Listagem completa
        $resAll = $this->get(route('site.contents.index'));
        $resAll->assertStatus(200);
        $resAll->assertSee('Foto Edição Especial');
        $resAll->assertSee('Vídeo Unboxing Lançamento');

        // Filtro por tipo=foto
        $resFoto = $this->get(route('site.contents.index', ['tipo' => 'foto']));
        $resFoto->assertStatus(200);
        $resFoto->assertSee('Foto Edição Especial');
        $resFoto->assertDontSee('Vídeo Unboxing Lançamento');

        // Filtro por tipo=video
        $resVideo = $this->get(route('site.contents.index', ['tipo' => 'video']));
        $resVideo->assertStatus(200);
        $resVideo->assertSee('Vídeo Unboxing Lançamento');
        $resVideo->assertDontSee('Foto Edição Especial');

        // Filtro por busca
        $resSearch = $this->get(route('site.contents.index', ['search' => 'Unboxing']));
        $resSearch->assertStatus(200);
        $resSearch->assertSee('Vídeo Unboxing Lançamento');
        $resSearch->assertDontSee('Foto Edição Especial');
    }

    public function test_content_show_page_renders_media_and_details(): void
    {
        $category = Category::factory()->create();

        $content = Content::factory()->create([
            'categoria_id' => $category->id,
            'status' => 'publicado',
            'titulo' => 'Matéria Especial da Semana',
            'descricao' => 'Descrição completa detalhada da matéria.',
            'slug' => 'materia-especial-da-semana',
        ]);

        $response = $this->get(route('site.contents.show', $content->slug));

        $response->assertStatus(200);
        $response->assertSee('Matéria Especial da Semana');
        $response->assertSee('Descrição completa detalhada da matéria.');

        // Rascunho não pode ser acessado publicamente (deve dar 404)
        $draft = Content::factory()->create([
            'categoria_id' => $category->id,
            'status' => 'rascunho',
            'slug' => 'conteudo-em-rascunho',
        ]);

        $resDraft = $this->get(route('site.contents.show', $draft->slug));
        $resDraft->assertStatus(404);
    }

    public function test_products_index_page_can_be_rendered_and_filtered(): void
    {
        $catJornais = Category::factory()->create(['nome' => 'Jornais']);
        $catGibis = Category::factory()->create(['nome' => 'Gibis']);

        $jornal = Product::factory()->create([
            'categoria_id' => $catJornais->id,
            'status' => 'publicado',
            'nome' => 'Folha de S.Paulo Diário',
            'preco' => 6.50,
        ]);

        $gibi = Product::factory()->create([
            'categoria_id' => $catGibis->id,
            'status' => 'publicado',
            'nome' => 'Turma da Mônica Jovem',
            'preco' => 12.00,
        ]);

        $res = $this->get(route('site.products.index'));
        $res->assertStatus(200);
        $res->assertSee('Folha de S.Paulo Diário');
        $res->assertSee('Turma da Mônica Jovem');

        // Filtro por categoria
        $resFiltered = $this->get(route('site.products.index', ['categoria_id' => $catGibis->id]));
        $resFiltered->assertStatus(200);
        $resFiltered->assertSee('Turma da Mônica Jovem');
        $resFiltered->assertDontSee('Folha de S.Paulo Diário');
    }

    public function test_product_show_page_renders_informative_price_and_whatsapp_link(): void
    {
        $category = Category::factory()->create(['nome' => 'Mangás']);

        $product = Product::factory()->create([
            'categoria_id' => $category->id,
            'status' => 'publicado',
            'nome' => 'One Piece Volume 100',
            'descricao' => 'Volume histórico de colecionador de One Piece.',
            'preco' => 34.90,
            'slug' => 'one-piece-volume-100',
        ]);

        $response = $this->get(route('site.products.show', $product->slug));

        $response->assertStatus(200);
        $response->assertSee('One Piece Volume 100');
        $response->assertSee('R$ 34,90');
        $response->assertSee('Consultar Disponibilidade no WhatsApp');
        $response->assertSee('wa.me');

        // Inativo não pode ser acessado publicamente
        $inactive = Product::factory()->create([
            'categoria_id' => $category->id,
            'status' => 'inativo',
            'slug' => 'item-esgotado-inativo',
        ]);

        $resInactive = $this->get(route('site.products.show', $inactive->slug));
        $resInactive->assertStatus(404);
    }

    public function test_contact_page_can_be_rendered_with_map_and_hours(): void
    {
        $response = $this->get(route('site.contact'));

        $response->assertStatus(200);
        $response->assertSee('Canais de Atendimento');
        $response->assertSee('Horário de Funcionamento');
        $response->assertSee('Localização da Banca no Mapa');
    }

    public function test_dynamic_sitemap_xml_can_be_rendered(): void
    {
        $category = Category::factory()->create();

        $content = Content::factory()->create([
            'categoria_id' => $category->id,
            'status' => 'publicado',
            'slug' => 'artigo-sitemap-teste',
        ]);

        $product = Product::factory()->create([
            'categoria_id' => $category->id,
            'status' => 'publicado',
            'slug' => 'item-sitemap-teste',
        ]);

        $response = $this->get(route('sitemap'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml');
        $response->assertSee('<urlset', false);
        $response->assertSee('artigo-sitemap-teste');
        $response->assertSee('item-sitemap-teste');
    }
}
