<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Configuration;
use App\Models\Content;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DatabaseAndModelsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_default_admin_user_is_seeded_and_can_authenticate(): void
    {
        $admin = User::where('email', 'admin@bancasantarita.com.br')->first();

        $this->assertNotNull($admin);
        $this->assertTrue(Hash::check('admin123', $admin->password));
    }

    public function test_categories_have_contents_and_products_relations(): void
    {
        $category = Category::where('slug', 'jornais-do-dia')->first();

        $this->assertNotNull($category);
        $this->assertGreaterThan(0, $category->contents()->count());
        $this->assertGreaterThan(0, $category->products()->count());
    }

    public function test_content_scopes_and_accessors(): void
    {
        $publishedCount = Content::published()->count();
        $this->assertGreaterThan(0, $publishedCount);

        $photos = Content::published()->photos()->get();
        $videos = Content::published()->videos()->get();

        $this->assertGreaterThan(0, $photos->count());
        $this->assertGreaterThan(0, $videos->count());

        $firstPhoto = $photos->first();
        $this->assertTrue($firstPhoto->isPhoto());
        $this->assertNotNull($firstPhoto->image_url);

        $firstVideo = $videos->first();
        $this->assertTrue($firstVideo->isVideo());
        $this->assertNotNull($firstVideo->video_embed_url);
    }

    public function test_product_informative_price_and_scopes(): void
    {
        $products = Product::published()->get();
        $this->assertGreaterThan(0, $products->count());

        $firstProduct = $products->first();
        $this->assertNotNull($firstProduct->category);
        $this->assertNotNull($firstProduct->image_url);

        if ($firstProduct->preco) {
            $this->assertStringStartsWith('R$ ', $firstProduct->formatted_price);
        }
    }

    public function test_configurations_singleton_and_whatsapp_url(): void
    {
        $config = Configuration::current();

        $this->assertEquals('Banca Santa Rita', $config->nome_banca);
        $this->assertNotEmpty($config->whatsapp);
        $this->assertStringContainsString('https://wa.me/', $config->getWhatsappUrl());
    }
}
