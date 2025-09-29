<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attribute extends Model
{
    protected $fillable = [
      'name',
      'label',
    ];
    public function __toString(): string
    {
        return $this->label ?? $this->name;
    }
    public function productAttributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class);
    }
}
