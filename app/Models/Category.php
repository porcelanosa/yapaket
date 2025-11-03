<?php

namespace App\Models;

use App\Models\Concerns\HasMenuItems;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @method Builder|self active()
 */
class Category extends Model implements HasMedia
{
    use InteractsWithMedia, HasMenuItems;

//    protected $with     = ['children'];
    protected $fillable = [
      'parent_id',
      'name',
      'title',
      'slug',
      'meta_description',
      'short_description',
      'description',
      'sort',
      'active',
    ];

    /**
     * Переопределяем Route Model Binding для автоматической загрузки связей
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'id', $value)
            ->with([
                'children',              // Подкатегории
                'children.products',     // Товары подкатегорий (для категорий 1-го уровня)
                'products'               // Товары текущей категории
            ])
            ->firstOrFail();
    }

    public function registerMediaCollections(?Media $media = null): void
    {
        $this
          ->addMediaCollection('category_image')
          ->withResponsiveImages();
    }

    public function registerMediaConversions(Media|null $media = null): void
    {
        $this
          ->addMediaConversion('category_thumb')
          ->width(368)
          ->height(232)
//          ->sharpen(10)
          ->format('webp')
          ->optimize()
          ->nonQueued();


        $this
          ->addMediaConversion('original_category_webp')
          ->format('webp')
          ->optimize()
          ->nonQueued()
          ->performOnCollections('category_image');

        $this
          ->addMediaConversion('original_category')
          ->optimize()
          ->nonQueued()
          ->performOnCollections('category_image');
    }

    public function __toString(): string
    {
        return $this->name ?? (string)$this->id;
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'category_product');
    }

    public function scopeActive($query): Builder|self
    {
        return $query->where('active', true);
    }

    public function getUrlAttribute(): string
    {
        return route('categories.show', $this->slug);
    }

    // App\Models\Category.php

//    public static function getTwoLevelMenu(): Collection
//    {
//        return self::with('children')
//                   ->whereNull('parent_id')
//                   ->where('active', true)
//                   ->orderBy('sort')
//                   ->get()
//                   ->map(function (self $category) {
//                       return [
//                         'id' => $category->id,
//                         'name' => $category->name,
//                         'url' => $category->url,
//                         'children' => $category->children
//                           ->where('active', true)
//                           ->sortBy('sort')
//                           ->values()
//                           ->map(fn(self $child) => [
//                             'id' => $child->id,
//                             'name' => $child->name,
//                             'url' => $child->url,
//                           ])->values(),
//                       ];
//                   })->values();
//    }
    public static function getTwoLevelMenu(): Collection
    {
        return self::with([
          'children' => fn($query) => $query->where('active', true)->orderBy('sort')
        ])
                   ->whereNull('parent_id')
                   ->where('active', true)
                   ->orderBy('sort')
                   ->get()
                   ->map(fn(self $category) => [
                     'id' => $category->id,
                     'name' => $category->name,
                     'url' => $category->url,
                     'children' => $category->children->map(fn(self $child) => [
                       'id' => $child->id,
                       'name' => $child->name,
                       'url' => $child->url,
                     ])->values(),
                   ])
                   ->values();
    }
}
