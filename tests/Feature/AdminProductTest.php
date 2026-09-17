<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminProductTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->admin = User::where('email', 'admin@bancasantarita.com.br')->first();
        $this->category = Category::first();
        Storage::fake('public');
    }

    public function test_guest_cannot_access_products(): void
    {
        $response = $this->get('/admin/produtos');
        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_view_products_list(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/produtos');

        $response->assertStatus(200);
        $response->assertSeeText('Itens em Exposição');
        $response->assertSeeText('Novo Item');
    }

    public function test_admin_can_view_create_product_page(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/produtos/create');

        $response->assertStatus(200);
        $response->assertSeeText('Cadastrar Item no Expositor');
    }

    public function test_admin_can_create_product_with_image_and_price(): void
    {
        $file = UploadedFile::fake()->create('revista.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($this->admin)->post('/admin/produtos', [
            'nome' => 'Revista Mundo dos Quadrinhos',
            'slug' => '',
            'descricao' => 'Edição especial comemorativa.',
            'imagem' => $file,
            'preco' => '19,90',
            'categoria_id' => $this->category->id,
            'destaque' => 'sim',
            'status' => 'publicado',
            'ordem' => 5,
        ]);

        $response->assertRedirect('/admin/produtos');
        $response->assertSessionHas('success');

        $product = Product::where('nome', 'Revista Mundo dos Quadrinhos')->first();
        $this->assertNotNull($product);
        $this->assertEquals('revista-mundo-dos-quadrinhos', $product->slug);
        $this->assertEquals(19.90, $product->preco);
        $this->assertEquals('sim', $product->destaque);
        $this->assertNotNull($product->imagem);

        Storage::disk('public')->assertExists($product->imagem);
    }

    public function test_product_creation_validation_fails_without_required_fields(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/produtos', [
            'nome' => '',
            'status' => '',
            'destaque' => '',
            'ordem' => '',
        ]);

        $response->assertSessionHasErrors(['nome', 'status', 'destaque', 'ordem']);
    }

    public function test_admin_can_update_product_and_replace_image(): void
    {
        $oldFile = UploadedFile::fake()->create('old-item.jpg', 100, 'image/jpeg');
        $oldPath = $oldFile->store('products', 'public');

        $product = Product::create([
            'nome' => 'Item Original',
            'slug' => 'item-original',
            'imagem' => $oldPath,
            'preco' => 10.00,
            'categoria_id' => $this->category->id,
            'destaque' => 'não',
            'status' => 'publicado',
            'ordem' => 1,
        ]);

        Storage::disk('public')->assertExists($oldPath);

        $newFile = UploadedFile::fake()->create('new-item.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($this->admin)->put("/admin/produtos/{$product->id}", [
            'nome' => 'Item Atualizado',
            'slug' => 'item-atualizado',
            'imagem' => $newFile,
            'preco' => '25,50',
            'categoria_id' => $this->category->id,
            'destaque' => 'sim',
            'status' => 'publicado',
            'ordem' => 1,
        ]);

        $response->assertRedirect('/admin/produtos');
        $response->assertSessionHas('success');

        Storage::disk('public')->assertMissing($oldPath);

        $product->refresh();
        $this->assertEquals('Item Atualizado', $product->nome);
        $this->assertEquals(25.50, $product->preco);
        Storage::disk('public')->assertExists($product->imagem);
    }

    public function test_admin_can_delete_product_and_clean_image(): void
    {
        $file = UploadedFile::fake()->create('deletar-item.jpg', 100, 'image/jpeg');
        $path = $file->store('products', 'public');

        $product = Product::create([
            'nome' => 'Item para Excluir',
            'slug' => 'item-para-excluir',
            'imagem' => $path,
            'categoria_id' => $this->category->id,
            'destaque' => 'não',
            'status' => 'publicado',
            'ordem' => 1,
        ]);

        Storage::disk('public')->assertExists($path);

        $response = $this->actingAs($this->admin)->delete("/admin/produtos/{$product->id}");

        $response->assertRedirect('/admin/produtos');
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
        Storage::disk('public')->assertMissing($path);
    }
}
