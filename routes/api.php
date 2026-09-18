<?php

use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\ConfigurationApiController;
use App\Http\Controllers\Api\ContentApiController;
use App\Http\Controllers\Api\ProductApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Pública - Expositor Digital Banca Santa Rita
|--------------------------------------------------------------------------
| Todos os endpoints retornam estritamente itens com status = publicado.
*/

Route::prefix('contents')->name('api.contents.')->group(function () {
    Route::get('/', [ContentApiController::class, 'index'])->name('index');
    Route::get('/featured', [ContentApiController::class, 'featured'])->name('featured');
    Route::get('/photos', [ContentApiController::class, 'photos'])->name('photos');
    Route::get('/videos', [ContentApiController::class, 'videos'])->name('videos');
    Route::get('/{slug}', [ContentApiController::class, 'show'])->name('show');
});

Route::get('/categories', [CategoryApiController::class, 'index'])->name('api.categories.index');

Route::prefix('products')->name('api.products.')->group(function () {
    Route::get('/', [ProductApiController::class, 'index'])->name('index');
    Route::get('/featured', [ProductApiController::class, 'featured'])->name('featured');
    Route::get('/{slug}', [ProductApiController::class, 'show'])->name('show');
});

Route::get('/configuration', [ConfigurationApiController::class, 'show'])->name('api.configuration.show');
