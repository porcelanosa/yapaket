<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver; // или Imagick\Driver

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::active()->get();
    }
    public function show(string $slug)
    {
        $post = Post::active()
                    ->where('slug', $slug)
                    ->firstOrFail();

        // Получаем медиафайл из коллекции 'post'
        $media = $post->getFirstMedia('post_image'); // null, если нет
//        if ($media) {
//            $path = $media->getPath('original_post_webp') ?? $media->getPath();
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
            ['width' => $media->width, 'height' => $media->height] = getImageDimensions($media, 'original_post_webp');
        }

        return view('posts.show', compact('post', 'media'));
    }
}
