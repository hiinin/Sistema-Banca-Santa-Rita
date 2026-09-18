<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ConfigurationController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Site\AboutController;
use App\Http\Controllers\Site\ContactController;
use App\Http\Controllers\Site\ContentPublicController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\ProductPublicController;
use App\Http\Controllers\Site\SitemapController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas Públicas do Site
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/sobre', [AboutController::class, 'index'])->name('site.about');
Route::get('/conteudos', [ContentPublicController::class, 'index'])->name('site.contents.index');
Route::get('/conteudo/{slug}', [ContentPublicController::class, 'show'])->name('site.contents.show');
Route::get('/produtos', [ProductPublicController::class, 'index'])->name('site.products.index');
Route::get('/produto/{slug}', [ProductPublicController::class, 'show'])->name('site.products.show');
Route::get('/contato', [ContactController::class, 'index'])->name('site.contact');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

/*
|--------------------------------------------------------------------------
| Autenticação Administrativa
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'create'])->name('login');
        Route::post('/login', [AuthController::class, 'store'])->name('login.store');
    });

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

        Route::get('/', function () {
            return redirect()->route('admin.dashboard');
        });

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // CRUD de Categorias (Fase 5)
        Route::resource('categorias', CategoryController::class)
            ->parameters(['categorias' => 'category'])
            ->names('categories');

        // CRUD de Conteúdos (Fotos e Vídeos - Fase 6)
        Route::resource('conteudos', ContentController::class)
            ->parameters(['conteudos' => 'content'])
            ->names('contents');

        // CRUD de Itens em Exposição / Produtos (Fase 7)
        Route::resource('produtos', ProductController::class)
            ->parameters(['produtos' => 'product'])
            ->names('products');

        // Configurações da Banca (Fase 8)
        Route::get('/configuracoes', [ConfigurationController::class, 'index'])->name('configurations.index');
        Route::put('/configuracoes', [ConfigurationController::class, 'update'])->name('configurations.update');
    });
});
