<?php

namespace App\Services;

use App\Models\Menu;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class MenuService
{
    protected static ?array $runtimeCache = []; // Runtime cache для избежания повторных запросов

    public function getMenuTree(string $slug): Collection
    {
        // Проверяем runtime cache
        if (isset(static::$runtimeCache[$slug])) {
            return static::$runtimeCache[$slug];
        }

        // Cache the menu for better performance - используем file cache
        $result = Cache::store('file')->remember("menu.{$slug}", now()->addHours(3), function () use ($slug) {
            $menu = Menu::where('slug', $slug)
                        ->with(['items.menuable', 'items.menu', 'items.children'])
                        ->first();

            if (!$menu) {
                return collect();
            }

            return $this->buildTree($menu->items);
        });

        // Сохраняем в runtime cache
        static::$runtimeCache[$slug] = $result;

        return $result;
    }

    protected function buildTree(Collection $items, $parentId = null): Collection
    {
        $branch = new Collection();

        foreach ($items as $item) {
            if ($item->parent_id==$parentId) {
                $children = $this->buildTree($items, $item->id);
                if ($children->isNotEmpty()) {
                    $item->children = $children;
                }
                $branch->push($item);
            }
        }

        return $branch;
    }

    /**
     * Очистить кеш меню
     */
    public static function clearCache(?string $slug = null): void
    {
        static::$runtimeCache = [];

        if ($slug) {
            Cache::store('file')->forget("menu.{$slug}");
        } else {
            // Очищаем все меню
            Cache::store('file')->forget('menu.header-menu');
            Cache::store('file')->forget('menu.footer-menu');
        }
    }
}
