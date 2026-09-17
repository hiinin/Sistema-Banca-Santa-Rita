<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Content;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminContentTest extends TestCase
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

    public function test_guest_cannot_access_contents(): void
    {
        $response = $this->get('/admin/conteudos');
        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_view_contents_list_with_filters(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/conteudos?tipo=foto');

        $response->assertStatus(200);
        $response->assertSeeText('Fotos & Vídeos');
        $response->assertSeeText('Novo Conteúdo');
    }

    public function test_admin_can_view_create_content_page(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/conteudos/create');

        $response->assertStatus(200);
        $response->assertSeeText('Cadastrar Novo Conteúdo');
        $response->assertSeeText('Fotografia / Galeria');
        $response->assertSeeText('Vídeo (YouTube / Vimeo / Arquivo)');
    }

    public function test_admin_can_create_photo_content_with_image_upload(): void
    {
        $file = UploadedFile::fake()->create('banca-foto.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($this->admin)->post('/admin/conteudos', [
            'titulo' => 'Foto Especial da Banca',
            'slug' => '',
            'tipo' => 'foto',
            'categoria_id' => $this->category->id,
            'imagem' => $file,
            'descricao' => 'Descrição da foto teste.',
            'ordem' => 1,
            'status' => 'publicado',
            'destaque' => 'sim',
            'data_publicacao' => '2026-09-17',
        ]);

        $response->assertRedirect('/admin/conteudos');
        $response->assertSessionHas('success');

        $content = Content::where('titulo', 'Foto Especial da Banca')->first();
        $this->assertNotNull($content);
        $this->assertEquals('foto', $content->tipo);
        $this->assertEquals('sim', $content->destaque);
        $this->assertNotNull($content->imagem);

        Storage::disk('public')->assertExists($content->imagem);
    }

    public function test_admin_can_create_video_content_with_youtube_url(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/conteudos', [
            'titulo' => 'Vídeo Institucional da Banca',
            'slug' => 'video-institucional-banca',
            'tipo' => 'video',
            'categoria_id' => $this->category->id,
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'descricao' => 'Vídeo tour da banca.',
            'ordem' => 2,
            'status' => 'publicado',
            'destaque' => 'não',
        ]);

        $response->assertRedirect('/admin/conteudos');
        $response->assertSessionHas('success');

        $content = Content::where('slug', 'video-institucional-banca')->first();
        $this->assertNotNull($content);
        $this->assertEquals('video', $content->tipo);
        $this->assertStringContainsString('youtube', $content->video_url);
    }

    public function test_photo_content_requires_image_file(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/conteudos', [
            'titulo' => 'Foto Sem Arquivo',
            'tipo' => 'foto',
            'categoria_id' => $this->category->id,
            'ordem' => 1,
            'status' => 'publicado',
            'destaque' => 'não',
        ]);

        $response->assertSessionHasErrors('imagem');
    }

    public function test_admin_can_update_content_and_replace_image(): void
    {
        $oldFile = UploadedFile::fake()->create('old-foto.jpg', 100, 'image/jpeg');
        $oldPath = $oldFile->store('contents', 'public');

        $content = Content::create([
            'titulo' => 'Conteúdo para Atualizar',
            'slug' => 'conteudo-para-atualizar',
            'tipo' => 'foto',
            'categoria_id' => $this->category->id,
            'imagem' => $oldPath,
            'ordem' => 1,
            'status' => 'publicado',
            'destaque' => 'não',
        ]);

        Storage::disk('public')->assertExists($oldPath);

        $newFile = UploadedFile::fake()->create('new-foto.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($this->admin)->put("/admin/conteudos/{$content->id}", [
            'titulo' => 'Conteúdo Atualizado com Nova Foto',
            'slug' => 'conteudo-atualizado-com-nova-foto',
            'tipo' => 'foto',
            'categoria_id' => $this->category->id,
            'imagem' => $newFile,
            'ordem' => 1,
            'status' => 'publicado',
            'destaque' => 'sim',
        ]);

        $response->assertRedirect('/admin/conteudos');
        $response->assertSessionHas('success');

        // Old file must be deleted, new file must exist
        Storage::disk('public')->assertMissing($oldPath);

        $content->refresh();
        $this->assertEquals('Conteúdo Atualizado com Nova Foto', $content->titulo);
        Storage::disk('public')->assertExists($content->imagem);
    }

    public function test_admin_can_delete_content_and_files_are_removed(): void
    {
        $file = UploadedFile::fake()->create('deletar.jpg', 100, 'image/jpeg');
        $path = $file->store('contents', 'public');

        $content = Content::create([
            'titulo' => 'Conteúdo para Deletar',
            'slug' => 'conteudo-para-deletar',
            'tipo' => 'foto',
            'categoria_id' => $this->category->id,
            'imagem' => $path,
            'ordem' => 1,
            'status' => 'publicado',
            'destaque' => 'não',
        ]);

        Storage::disk('public')->assertExists($path);

        $response = $this->actingAs($this->admin)->delete("/admin/conteudos/{$content->id}");

        $response->assertRedirect('/admin/conteudos');
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('contents', ['id' => $content->id]);
        Storage::disk('public')->assertMissing($path);
    }
}
