<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Image extends Model
{
    protected $fillable = [
      'path',
      'title',
      'caption',
      'alt',
      'type',
      'sort',
      'is_active',
      'filesize',
      'width',
      'height',
      'mime_type',
      'hash',
      'meta',
    ];

    protected $casts = [
      'is_active' => 'boolean',
      'meta' => 'array',
    ];

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

    // Удобный аксессор для получения абсолютного URL
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }
}
