<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Media\MediaReorderController;
use App\Http\Controllers\Media\ProductImagesController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class, 'index'])->name('home');
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('test', [SiteController::class, 'index'])->name('test');

// API-подобные или служебные маршруты
Route::post('/reorder/{model}', [MediaReorderController::class, 'reorder'])->name('media.reorder');
Route::get('/img/{imageId}/{width}/{height?}/{type?}/{quality?}', [ProductImagesController::class, 'resize'])
     ->whereNumber(['imageId', 'width', 'height',  'quality'])
     ->name('product-image.resize');

// Страницы с фиксированными URL-префиксами
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');

Route::get('/contacts', [PageController::class, 'show'])->defaults('slug', 'contacts')->name('contacts');
Route::get('/posts/{slug}', [PostController::class, 'show'])->name('posts.show');
Route::get('/pages', [PageController::class, 'index'])->name('pages.index');
Route::get('/pages/{slug}', [PageController::class, 'show'])->name('pages.show');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

// Динамические маршруты с модельными параметрами (самые общие — внизу!)
Route::get('/{category:slug?}/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');

/*require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';*/