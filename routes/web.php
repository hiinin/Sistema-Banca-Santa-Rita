<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas Públicas do Site
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
})->name('home');

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

        // Rotas das fases seguintes (stubs temporários)
        Route::get('/conteudos', fn () => redirect()->route('admin.dashboard'))->name('contents.index');
        Route::get('/conteudos/criar', fn () => redirect()->route('admin.dashboard'))->name('contents.create');
        Route::get('/conteudos/{id}/editar', fn () => redirect()->route('admin.dashboard'))->name('contents.edit');
        Route::get('/produtos', fn () => redirect()->route('admin.dashboard'))->name('products.index');
        Route::get('/produtos/criar', fn () => redirect()->route('admin.dashboard'))->name('products.create');
        Route::get('/configuracoes', fn () => redirect()->route('admin.dashboard'))->name('configurations.index');
    });
});
