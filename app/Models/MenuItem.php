<?php

namespace App\Models;

use App\Services\MenuService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MenuItem extends Model
{
    protected $fillable = [
        'menu_id',
        'parent_id',
        'order',
        'title',
        'url',
        'route',
        'menuable_type',
        'menuable_id',
    ];

    /**
     * Очищаем кеш при изменении пунктов меню
     */
    protected static function booted(): void
    {
        static::saved(function ($menuItem) {
            if ($menuItem->menu) {
                MenuService::clearCache($menuItem->menu->slug);
            }
        });

        static::deleted(function ($menuItem) {
            if ($menuItem->menu) {
                MenuService::clearCache($menuItem->menu->slug);
            }
        });
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order');
    }

    public function menuable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the url for the menu item.
     */
    public function getUurlAttribute($value)
    {
        if ($this->menuable) {
//            $appUrl = config('app.url');
//            return str_replace($appUrl, '', $this->menuable->url);
            return parse_url($this->menuable->url, PHP_URL_PATH);
        }
        
        if ($this->route) {
            return route($this->route);
        }
        if($this->url) {
            return $this->url;
        }
        return $value;
    }
}
