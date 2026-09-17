<?php

namespace Tests\Feature;

use App\Models\Configuration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminConfigurationTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->admin = User::where('email', 'admin@bancasantarita.com.br')->first();
        Storage::fake('public');
    }

    public function test_guest_cannot_access_configurations(): void
    {
        $response = $this->get('/admin/configuracoes');
        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_view_configurations_page(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/configuracoes');

        $response->assertStatus(200);
        $response->assertSee('Configurações da Banca');
        $response->assertSee('Banca Santa Rita');
        $response->assertSee('Salvar Configurações da Banca');
    }

    public function test_admin_can_update_configurations(): void
    {
        $response = $this->actingAs($this->admin)->put('/admin/configuracoes', [
            'nome_banca' => 'Banca Santa Rita Nova',
            'descricao' => 'Nova descrição de teste.',
            'endereco' => 'Rua do Comércio, 123',
            'telefone' => '(15) 3333-4444',
            'whatsapp' => '15988887777',
            'email' => 'novo@bancasantarita.com.br',
            'instagram' => 'bancasantaritanova',
            'facebook' => 'bancasantaritanova',
            'horario' => 'Das 07h às 19h',
            'latitude' => '-23.5000',
            'longitude' => '-47.4500',
            'texto_sobre' => 'História renovada da banca.',
        ]);

        $response->assertRedirect('/admin/configuracoes');
        $response->assertSessionHas('success');

        $config = Configuration::current();
        $this->assertEquals('Banca Santa Rita Nova', $config->nome_banca);
        $this->assertEquals('15988887777', $config->whatsapp);
        $this->assertEquals('Rua do Comércio, 123', $config->endereco);
    }

    public function test_admin_can_upload_logo_and_favicon(): void
    {
        $logo = UploadedFile::fake()->create('custom-logo.png', 100, 'image/png');
        $favicon = UploadedFile::fake()->create('custom-favicon.png', 20, 'image/png');

        $response = $this->actingAs($this->admin)->put('/admin/configuracoes', [
            'nome_banca' => 'Banca Santa Rita com Logo',
            'logo' => $logo,
            'favicon' => $favicon,
        ]);

        $response->assertRedirect('/admin/configuracoes');
        $response->assertSessionHas('success');

        $config = Configuration::current();
        $this->assertNotNull($config->logo);
        $this->assertNotNull($config->favicon);

        Storage::disk('public')->assertExists($config->logo);
        Storage::disk('public')->assertExists($config->favicon);
    }

    public function test_configuration_validation_requires_nome_banca(): void
    {
        $response = $this->actingAs($this->admin)->put('/admin/configuracoes', [
            'nome_banca' => '',
            'email' => 'invalid-email',
        ]);

        $response->assertSessionHasErrors(['nome_banca', 'email']);
    }
}
