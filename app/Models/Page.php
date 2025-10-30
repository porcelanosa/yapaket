<?php

namespace App\Models;

use App\Models\Concerns\HasMenuItems;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Page extends Model implements HasMedia
{
    use InteractsWithMedia/*, HasMenuItems*/;

//    protected $with     = ['products'];
    protected $fillable = [
      'name',
      'title',
      'slug',
      'meta_description',
      'short_description',
      'description',
      'sort',
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
          ->nonQueued()
        ;
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


    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
