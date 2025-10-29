<?php

declare(strict_types = 1);

namespace App\Helpers;


use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
class ProductImageHelper {
    public static function setImagesDimensions(Product $product): Product {

        $manager = new ImageManager(new Driver());

        // Работаем напрямую с коллекцией productImages
        $product->productImages->each(function ($image) use ($manager) {
            $cacheKey = "product_image_dimensions_{$image->id}";

            $dimensions = Cache::remember($cacheKey, 0, function () use ($image, $manager) {
                try {
                    $imagePath = $image->full_path;
                    if (!file_exists($imagePath)) {
                        return ['width' => 0, 'height' => 0];
                    }

                    $imageInstance = $manager->read($imagePath);

                    return [
                      'width'  => $imageInstance->width(),
                      'height' => $imageInstance->height()
                    ];
                } catch (\Exception $e) {
                    return ['width' => 0, 'height' => 0];
                }
            });

            // Добавляем свойства к объекту
            $image->width  = $dimensions['width'];
            $image->height = $dimensions['height'];
        });
        return $product;
    }
}