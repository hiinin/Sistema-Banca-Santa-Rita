<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Content;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->admin = User::where('email', 'admin@bancasantarita.com.br')->first();
    }

    public function test_guest_cannot_access_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/admin/login');
    }

    public function test_authenticated_admin_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Visão Geral da Banca');
        $response->assertSee('Total de Conteúdos');
        $response->assertSee('Publicados no Site');
        $response->assertSee('Em Rascunho');
        $response->assertSee('Total de Fotos');
        $response->assertSee('Total de Vídeos');
        $response->assertSee('Itens em Exposição');
        $response->assertSee('Total de Categorias');
    }

    public function test_dashboard_displays_accurate_metric_numbers(): void
    {
        $totalConteudos = Content::count();
        $totalItens = Product::count();
        $totalCategorias = Category::count();

        $response = $this->actingAs($this->admin)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee((string) $totalConteudos);
        $response->assertSee((string) $totalItens);
        $response->assertSee((string) $totalCategorias);
    }

    public function test_dashboard_lists_recent_contents(): void
    {
        $latestContent = Content::latest()->first();

        $response = $this->actingAs($this->admin)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee($latestContent->titulo);
    }
}
