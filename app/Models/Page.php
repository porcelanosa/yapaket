<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @method static Builder|Page inMain()
 * @method static Builder|Page active()
 * @property string|null $component
 * @property string|null $slug
 */
class Page extends Model implements HasMedia
{
    use InteractsWithMedia/*, HasMenuItems*/
        ;

//    protected $with     = ['products'];
    protected $fillable = [
      'name',
      'title',
      'slug',
      'meta_description',
      'short_description',
      'description',
      'component',
      'sort',
      'show_in_main',
      'active',
    ];
    protected $casts    = [
      'active' => 'boolean',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('page_image')->singleFile();
    }

    public function registerMediaConversions(Media|null $media = null): void
    {
        $this
          ->addMediaConversion('page_thumb')
          ->width(368)
          ->height(232)
//          ->sharpen(10)
          ->format('webp')
          ->optimize()
          ->nonQueued();
        $this
          ->addMediaConversion('original_page_webp')
          ->format('webp')
          ->withResponsiveImages()
          ->optimize()
          ->nonQueued()
          ->performOnCollections('page_image');

        $this
          ->addMediaConversion('original_page')
          ->optimize()
          ->nonQueued()
          ->performOnCollections('page_image');
    }

    public function __toString(): string
    {
        return $this->name ?? (string)$this->id;
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'page_product');
    }

    /**
     * Get the url for the page.
     */
    public function getUrlAttribute(): string
    {
        return route('pages.show', $this->slug);
    }

    public function getComponentAttribute(): ?string
    {
        return $this->attributes['component'] ?: null;
    }

    public function scopeActive($query): Builder|self
    {
        return $query->where('active', true);
    }

    public function scopeInMain($query): Builder|self
    {
        return $query->where('active', true)->where('show_in_main', true)->orderBy('sort');
    }

    protected static function booted(): void
    {
        static::saved(function () {
            self::clearCache();
        });

        static::deleted(function () {
            self::clearCache();
        });
    }

    public static function clearCache(): void
    {
//        static::$runtimeCache = [];
        Cache::store('file')->forget('home_pages');
    }
}
