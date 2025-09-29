<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Page extends Model
{
    protected $with = ['products'];
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

    public function __toString(): string
    {
        return $this->name ?? (string)$this->id;
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'page_product');
    }
}
