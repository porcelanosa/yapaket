<?php

namespace App\Http\Controllers;

use App\Models\Page;

// или Imagick\Driver

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::active()->get();
    }
    public function show(string $slug)
    {
        $page = Page::active()
                    ->where('slug', $slug)
                    ->firstOrFail();

        // Получаем медиафайл из коллекции 'page'
        $media = $page->getFirstMedia('page_image'); // null, если нет
//        if ($media) {
//            $path = $media->getPath('original_page_webp') ?? $media->getPath();
//
//            if (is_file($path)) {
//                // ✅ создаём менеджер
//                $manager = new ImageManager(new Driver());
//
//                // ✅ читаем изображение
//                $image = $manager->read($path);
//
//                // ✅ получаем размеры
//                $media->width = $image->width();
//                $media->height = $image->height();
//            }
//        }
        if ($media) {
            ['width' => $media->width, 'height' => $media->height] = getImageDimensions($media, 'original_page_webp');
        }

        return view('pages.show', compact('page', 'media'));
    }
}
