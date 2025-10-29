<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

use function PHPUnit\Framework\matches;

// или Imagick\Driver

class ProductImagesController extends Controller
{
    protected ImageManager $imageManager;

    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver());
    }

    /**
     * Динамическое изменение размера и оптимизация изображения.
     *
     * @return \Illuminate\Http\Response
     *
     * @param  int       $width
     * @param  int|null  $height
     * @param  string    $type
     * @param  int       $quality
     * @param  int       $imageId
     */
    public function resize(int $imageId, int $width, ?int $height = null, string $type = 'webp', $quality = 80)
    {
        // Валидация размеров
        if ($width < 1 || $width > 1920) {
            abort(400, 'Invalid width');
        }
        if ($height !== null && ($height < 1 || $height > 1920)) {
            abort(400, 'Invalid height');
        }

        // Находим изображение
        $image = ProductImage::findOrFail($imageId);

        if (!Storage::disk('public')->exists($image->path)) {
            abort(404);
        }

        // Формируем ключ кэша
        $cacheSuffix = $height ? "{$width}x{$height}" : "w{$width}";
        $cachePath = "cache/product_images/{$imageId}/{$cacheSuffix}.{$type}";

        // Проверяем кэш
        if (Storage::disk('public')->exists($cachePath)) {
            $content = Storage::disk('public')->get($cachePath);
            return response($content)
              ->header('Content-Type', "image/{$type}")
              ->header('Cache-Control', 'public, max-age=31536000');
        }

        // Читаем оригинальное изображение
        $originalPath = Storage::disk('public')->path($image->path);
        $img = $this->imageManager->read($originalPath);

        // Изменяем размер
        if ($height) {
            $img->cover($width, $height); // обрезка до точного размера
        } else {
            $img->scale(width: $width); // пропорционально по ширине
        }

        // Оптимизируем и конвертируем в WebP
        $optimized = match ($type) {
            'jpg' => $img->toJpeg(quality: $quality),
            'png' => $img->toPng(quality: $quality),
            default => $img->toWebp(quality: $quality),
        };
        // Сохраняем в кэш
        Storage::disk('public')->put($cachePath, $optimized);

        return response($optimized)
          ->header('Content-Type', "image/{$type}")
          ->header('Cache-Control', 'public, max-age=31536000');
    }
}