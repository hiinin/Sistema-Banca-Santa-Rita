<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'email' => 'admin@bancasantarita.com.br',
            'password' => Hash::make('admin123'),
        ]);
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
        $response->assertSee('Painel Administrativo');
        $response->assertSee('Entrar no Painel');
    }

    public function test_admin_can_authenticate_with_valid_credentials(): void
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@bancasantarita.com.br',
            'password' => 'admin123',
        ]);

        $this->assertAuthenticatedAs($this->admin);
        $response->assertRedirect('/admin/dashboard');
    }

    public function test_admin_cannot_authenticate_with_invalid_password(): void
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@bancasantarita.com.br',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_admin_cannot_authenticate_with_invalid_email_format(): void
    {
        $response = $this->post('/admin/login', [
            'email' => 'not-an-email',
            'password' => 'admin123',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_admin_can_logout_safely(): void
    {
        $this->actingAs($this->admin);

        $response = $this->post('/admin/logout');

        $this->assertGuest();
        $response->assertRedirect('/admin/login');
    }

    public function test_unauthenticated_user_cannot_access_admin_routes(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/admin/login');
    }

    public function test_login_is_rate_limited_after_multiple_failures(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/admin/login', [
                'email' => 'admin@bancasantarita.com.br',
                'password' => 'wrong-password',
            ]);
        }

        // 6th attempt should be blocked by rate limiter
        $response = $this->post('/admin/login', [
            'email' => 'admin@bancasantarita.com.br',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
    }
}
