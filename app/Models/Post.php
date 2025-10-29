<?php

namespace App\Models;

use App\Models\Concerns\HasMenuItems;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Post extends Model implements HasMedia
{
    use InteractsWithMedia/*, HasMenuItems*/;

    protected $fillable = [
      'name',
      'slug',
      'title',
      'announce',
      'meta_description',
      'content',
      'sort',
      'active',
    ];

    protected $casts = [
      'active' => 'boolean',
      'post_image' => 'array',
    ];
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('post_image')->singleFile();
    }

    public function registerMediaConversions(Media|null $media = null): void
    {
        $this
          ->addMediaConversion('post_thumb')
          ->width(368)
          ->height(232)
          ->sharpen(10)
          ->optimize()
          ->format('webp')
          ->nonQueued()
          ->performOnCollections('post_image'); // Только для конкретной коллекции

        $this
          ->addMediaConversion('original_post_webp')
          ->format('webp')
          // ->withResponsiveImages() // УБИРАЕМ - это потребляет много памяти
          ->optimize()
          ->nonQueued()
          ->performOnCollections('post_image');

        $this
          ->addMediaConversion('original_post')
          ->optimize()
          ->nonQueued()
          ->performOnCollections('post_image');
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function getUrlAttribute(): string
    {
        return route('posts.show', $this->slug);
    }

    protected static function boot(): void
    {
        parent::boot();

//        static::updating(function ($model) {
//            \Log::info('Post updating', [
//              'id' => $model->id,
//              'request' => request()->all()
//            ]);
//        });
    }
}
