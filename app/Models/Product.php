<?php

namespace App\Models;

use App\Models\Concerns\HasMenuItems;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @extends Model
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\ProductImage[] $productImages
 */
class Product extends Model implements HasMedia
{
    use InteractsWithMedia, HasMenuItems;

//    protected    $with = ['attributes', 'categories'/*, 'pages'*//*, 'images'*/];

    protected $fillable = [
      'name',
      'title',
      'slug',
      'meta_description',
      'short_description',
      'description',
      'price',
      'circulation',
      'sort',
      'status',
//      'images',
    ];

    protected $casts = [
      'images' => 'collection',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images');
//        $this->addMediaCollection('productImages');
    }

    public function registerMediaConversions(Media|null $media = null): void
    {
        $this
          ->addMediaConversion('thumb')
          ->width(368)
          ->height(232)
          ->sharpen(10)
          ->format('webp')
          ->optimize()
          ->nonQueued();
    }

    public function __toString(): string
    {
        return $this->name ?? $this->title ?? (string)$this->id;
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class);
    }

//    public function images(): MorphMany
//    {
//        return $this->morphMany(Image::class, 'imageable')->orderBy('sort');
//    }
    public function productImages(): HasMany
    {
        // Создаем HasMany связь, но с условием для полиморфной связи
//        return $this->hasMany(Image::class, 'imageable_id')
//                    ->where('imageable_type', Product::class) // или self::class
//                    ->orderBy('sort');
        return $this->hasMany(ProductImage::class)->where('is_primary', false);
    }

    /**
     * Получить основное изображение продукта
     */
    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }
//
//    /**
//     * Получить первое основное изображение или первое доступное
//     */
//    public function getPrimaryImageAttribute(): ?ProductImage
//    {
//        return $this->images->firstWhere('is_primary', true)
//          ?? $this->images->first();
//    }

    /**
     * Связь многие-ко-многим с категориями
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_product', 'product_id', 'category_id');
    }

    /**
     * Связь многие-ко-многим со страницами
     */
    public function pages(): BelongsToMany
    {
        return $this->belongsToMany(Page::class, 'page_product', 'product_id', 'page_id');
    }

    public function relatedProducts(): BelongsToMany
    {
        return $this->belongsToMany(
          Product::class,
          'product_related',
          'product_id',
          'related_product_id',
        );
    }

    /**
     * Обратная связь (чтобы иметь доступ к тем, кто указал этот товар как похожий)
     */
    public function relatedTo(): BelongsToMany
    {
        return $this->belongsToMany(
          Product::class,
          'product_related',
          'related_product_id',
          'product_id',
        );
    }

    /**
     * Get the url for the product.
     */
    public function getUrlAttribute(): string
    {
        return route('products.show', $this->slug);
    }
}
