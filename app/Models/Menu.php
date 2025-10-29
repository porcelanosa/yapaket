<?php

namespace App\Models;

use App\Services\MenuService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['name', 'slug', 'title', 'description'];

    /**
     * Очищаем кеш при изменении меню
     */
    protected static function booted(): void
    {
        static::saved(function ($menu) {
            MenuService::clearCache($menu->slug);
        });

        static::deleted(function ($menu) {
            MenuService::clearCache($menu->slug);
        });
    }

    public function items()
    {
        return $this->hasMany(MenuItem::class)->orderBy('order');
    }

    public function rootItems()
    {
        return $this->hasMany(MenuItem::class)->whereNull('parent_id')->orderBy('order');
    }
}
