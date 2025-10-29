<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Media\MediaReorderController;
use App\Http\Controllers\Media\ProductImagesController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;
use UniSharp\LaravelFilemanager\Lfm;

Route::get('/', [SiteController::class, 'index'])->name('home');

Route::get('test', [SiteController::class, 'index'])->name('test');

//Route::get('dashboard', function () {
//    return Inertia::render('Dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');

//Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
//    Lfm::routes();
//});
Route::group([
  'prefix' => 'laravel-filemanager',
  'middleware' => ['moonshine', 'auth']  // 'auth' с guard 'moonshine' из config/moonshine.php
], function () {
    Lfm::routes();
});
// API-подобные или служебные маршруты
Route::post('/reorder/{model}', [MediaReorderController::class, 'reorder'])->name('media.reorder');
Route::get('/img/{imageId}/{width}/{height?}', [ProductImagesController::class, 'resize'])
     ->whereNumber(['imageId', 'width', 'height'])
     ->name('product-image.resize');

// Страницы с фиксированными URL-префиксами
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{slug}', [PostController::class, 'show'])->name('posts.show');
Route::get('/pages', [PageController::class, 'index'])->name('pages.index');
Route::get('/pages/{slug}', [PageController::class, 'show'])->name('pages.show');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

// Динамические маршруты с модельными параметрами (самые общие — внизу!)
Route::get('/{category:slug?}/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');
require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
