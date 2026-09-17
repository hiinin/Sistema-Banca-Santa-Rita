<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Content;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCategoryTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->admin = User::where('email', 'admin@bancasantarita.com.br')->first();
    }

    public function test_guest_cannot_access_categories(): void
    {
        $response = $this->get('/admin/categorias');
        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_view_categories_list(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/categorias');

        $response->assertStatus(200);
        $response->assertSee('Categorias');
        $response->assertSee('Nova Categoria');
        $response->assertSee('Jornais do Dia');
    }

    public function test_admin_can_view_create_category_page(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/categorias/create');

        $response->assertStatus(200);
        $response->assertSee('Cadastrar Nova Categoria');
    }

    public function test_admin_can_create_category_with_auto_generated_slug(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/categorias', [
            'nome' => 'Passatempos & Palavras Cruzadas',
            'slug' => '',
            'descricao' => 'Revistas de sudoku, cruzadas e caça-palavras.',
            'status' => 'ativo',
            'ordem' => 10,
        ]);

        $response->assertRedirect('/admin/categorias');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('categories', [
            'nome' => 'Passatempos & Palavras Cruzadas',
            'slug' => 'passatempos-palavras-cruzadas',
            'status' => 'ativo',
            'ordem' => 10,
        ]);
    }

    public function test_category_creation_validation_fails_without_required_fields(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/categorias', [
            'nome' => '',
            'status' => '',
            'ordem' => '',
        ]);

        $response->assertSessionHasErrors(['nome', 'status', 'ordem']);
    }

    public function test_admin_can_view_edit_category_page(): void
    {
        $category = Category::where('slug', 'jornais-do-dia')->first();

        $response = $this->actingAs($this->admin)->get("/admin/categorias/{$category->id}/edit");

        $response->assertStatus(200);
        $response->assertSee('Editar Categoria');
        $response->assertSee($category->nome);
    }

    public function test_admin_can_update_category(): void
    {
        $category = Category::where('slug', 'jornais-do-dia')->first();

        $response = $this->actingAs($this->admin)->put("/admin/categorias/{$category->id}", [
            'nome' => 'Jornais do Dia & Matutinos',
            'slug' => 'jornais-do-dia-matutinos',
            'descricao' => 'Atualizado com jornais de circulação estadual.',
            'status' => 'ativo',
            'ordem' => 2,
        ]);

        $response->assertRedirect('/admin/categorias');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'nome' => 'Jornais do Dia & Matutinos',
            'slug' => 'jornais-do-dia-matutinos',
        ]);
    }

    public function test_admin_can_delete_empty_category(): void
    {
        $category = Category::create([
            'nome' => 'Categoria Temporaria Vazia',
            'slug' => 'categoria-temporaria-vazia',
            'status' => 'ativo',
            'ordem' => 99,
        ]);

        $response = $this->actingAs($this->admin)->delete("/admin/categorias/{$category->id}");

        $response->assertRedirect('/admin/categorias');
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_admin_cannot_delete_category_with_associated_contents_or_products(): void
    {
        $category = Category::where('slug', 'jornais-do-dia')->first();

        // Ensure category has content or product
        $this->assertTrue($category->contents()->count() > 0 || $category->products()->count() > 0);

        $response = $this->actingAs($this->admin)->delete("/admin/categorias/{$category->id}");

        $response->assertRedirect('/admin/categorias');
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }
}
