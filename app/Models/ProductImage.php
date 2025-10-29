<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Psr\Http\Message\UploadedFileInterface;

class ProductImage extends Model
{
    protected $fillable = [
      'product_id',
      'path',
      'alt',
      'title',
      'sort',
      'is_primary',
    ];

    protected $casts = [
      'is_primary' => 'boolean',
      'sort'       => 'integer',
//      'path'       => 'collection',
    ];

    protected $appends = ['url', 'full_path'];

//    public function setPathAttribute($value): void
//    {
//        if ($value instanceof UploadedFile) {
//            $this->attributes['path'] = $value->store('images/products', 'public');
//
//            return;
//        }
//
//        if ($value instanceof UploadedFileInterface) {
//            $stream   = $value->getStream();
//            $filename = uniqid() . '-' . $value->getClientFilename();
//
//            \Illuminate\Support\Facades\Storage::disk('public')
//                                               ->put("images/products/{$filename}", $stream);
//
//            $this->attributes['path'] = "images/products/{$filename}";
//
//            return;
//        }
//
//        $this->attributes['path'] = is_string($value) ? $value : null;
//    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getUrlAttribute(): ?string
    {
        return $this->path ? Storage::disk('public')->url($this->path) : null;
    }

    public function getFullPathAttribute(): ?string
    {
        return $this->path ? Storage::disk('public')->path($this->path) : null;
    }

    public function setPrimary(): bool
    {
        self::where('product_id', $this->product_id)
            ->where('id', '!=', $this->id)
            ->update(['is_primary' => false]);

        return $this->update(['is_primary' => true]);
    }

    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort')->orderBy('id');
    }

    protected static function booted(): void
    {
        static::deleting(function (ProductImage $image) {
            if ($image->path && Storage::disk('public')->exists($image->path)) {
                Storage::disk('public')->delete($image->path);
            }
        });

        static::created(function (ProductImage $image) {
            if (!$image->is_primary) {
                $hasOtherPrimary = self::where('product_id', $image->product_id)
                                       ->where('id', '!=', $image->id)
                                       ->where('is_primary', true)
                                       ->exists();

                if (!$hasOtherPrimary) {
                    $image->update(['is_primary' => true]);
                }
            }
        });
    }
}
