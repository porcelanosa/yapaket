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

// Laravel FileManager - ручная регистрация маршрутов - устанвливается автоматически в
//Route::prefix('laravel-filemanager')
//     ->middleware(['moonshine', 'auth',
//       \UniSharp\LaravelFilemanager\Middlewares\CreateDefaultFolder::class,
//       \UniSharp\LaravelFilemanager\Middlewares\MultiUser::class
//     ])
//     ->name('unisharp.lfm.')
//     ->group(function () {
//         // Main layout
//         Route::get('/', [\UniSharp\LaravelFilemanager\Controllers\LfmController::class, 'show'])
//              ->name('show');
//
//         // Errors
//         Route::get('/errors', [\UniSharp\LaravelFilemanager\Controllers\LfmController::class, 'getErrors'])
//              ->name('getErrors');
//
//         // Upload
//         Route::any('/upload', [\UniSharp\LaravelFilemanager\Controllers\UploadController::class, 'upload'])
//              ->name('upload');
//
//         // List items
//         Route::get('/jsonitems', [\UniSharp\LaravelFilemanager\Controllers\ItemsController::class, 'getItems'])
//              ->name('getItems');
//
//         Route::get('/move', [\UniSharp\LaravelFilemanager\Controllers\ItemsController::class, 'move'])
//              ->name('move');
//
//         Route::get('/domove', [\UniSharp\LaravelFilemanager\Controllers\ItemsController::class, 'doMove'])
//              ->name('doMove');
//
//         // Folders
//         Route::get('/newfolder', [\UniSharp\LaravelFilemanager\Controllers\FolderController::class, 'getAddfolder'])
//              ->name('getAddfolder');
//
//         Route::get('/folders', [\UniSharp\LaravelFilemanager\Controllers\FolderController::class, 'getFolders'])
//              ->name('getFolders');
//
//         // Crop
//         Route::get('/crop', [\UniSharp\LaravelFilemanager\Controllers\CropController::class, 'getCrop'])
//              ->name('getCrop');
//
//         Route::get('/cropimage', [\UniSharp\LaravelFilemanager\Controllers\CropController::class, 'getCropImage'])
//              ->name('getCropImage');
//
//         Route::get('/cropnewimage', [\UniSharp\LaravelFilemanager\Controllers\CropController::class, 'getNewCropImage'])
//              ->name('getNewCropImage');
//
//         // Rename
//         Route::get('/rename', [\UniSharp\LaravelFilemanager\Controllers\RenameController::class, 'getRename'])
//              ->name('getRename');
//
//         // Resize
//         Route::get('/resize', [\UniSharp\LaravelFilemanager\Controllers\ResizeController::class, 'getResize'])
//              ->name('getResize');
//
//         Route::get('/doresize', [\UniSharp\LaravelFilemanager\Controllers\ResizeController::class, 'performResize'])
//              ->name('performResize');
//
//         Route::get('/doresizenew', [\UniSharp\LaravelFilemanager\Controllers\ResizeController::class, 'performResizeNew'])
//              ->name('performResizeNew');
//
//         // Download
//         Route::get('/download', [\UniSharp\LaravelFilemanager\Controllers\DownloadController::class, 'getDownload'])
//              ->name('getDownload');
//
//         // Delete
//         Route::get('/delete', [\UniSharp\LaravelFilemanager\Controllers\DeleteController::class, 'getDelete'])
//              ->name('getDelete');
//     });

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