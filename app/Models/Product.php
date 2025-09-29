<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $with = ['attributes', 'categories'];
    protected $fillable = [
      'name',
      'title',
      'slug',
      'meta_description',
      'short_description',
      'description',
      'price',
      'sort',
      'status',
    ];
    public function __toString(): string
    {
        return $this->name ?? $this->title ?? (string) $this->id;
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class);
    }
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
}
