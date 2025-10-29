<?php

declare(strict_types = 1);

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver; // или Gd\Driver

if (! function_exists('getImageDimensions')) {
    /**
     * Возвращает размеры изображения (width, height) для MediaLibrary файла.
     *
     * @param  Media|null  $media
     * @param  string|null  $conversion  (например 'post_thumb' или null для оригинала)
     * @return array{width: int|null, height: int|null}
     */
    function getImageDimensions(?Media $media, ?string $conversion = null): array
    {
        if (! $media) {
            return ['width' => null, 'height' => null];
        }

        $path = $media->getPath($conversion) ?? $media->getPath();

        if (! $path || ! is_file($path)) {
            return ['width' => null, 'height' => null];
        }

        try {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($path);

            return [
              'width' => $image->width(),
              'height' => $image->height(),
            ];
        } catch (\Throwable $e) {
            // Если файл повреждён или не изображение
            return ['width' => null, 'height' => null];
        }
    }
}
