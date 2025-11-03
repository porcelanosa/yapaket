<?php

declare(strict_types = 1);

namespace App\MoonShine\Layouts;

use App\MoonShine\Pages\SettingsPage;
use App\MoonShine\Resources\AttributeResource;
use App\MoonShine\Resources\CategoryResource;
use App\MoonShine\Resources\MenuItemResource;
use App\MoonShine\Resources\MenuResource;
use App\MoonShine\Resources\PageResource;
use App\MoonShine\Resources\PostResource;
use App\MoonShine\Resources\ProductResource;
use MoonShine\ColorManager\ColorManager;
use MoonShine\Contracts\ColorManager\ColorManagerContract;
use MoonShine\Laravel\Layouts\CompactLayout;
use MoonShine\Laravel\Resources\MoonShineUserResource;
use MoonShine\Laravel\Resources\MoonShineUserRoleResource;
use MoonShine\MenuManager\MenuGroup;
use MoonShine\MenuManager\MenuItem;
use MoonShine\UI\Components\{Layout\Layout};

final class MoonShineLayout extends CompactLayout
{
    protected function assets(): array
    {
        return [
          ...parent::assets(),
        ];
    }

    protected function menu(): array
    {
        return [
          MenuItem::make('Пакеты', ProductResource::class)->icon('cube'),
          MenuItem::make('Категории', CategoryResource::class)->icon('folder'),
          MenuItem::make('Страницы', PageResource::class)->icon('clipboard-document'),
          MenuItem::make('Новости', PostResource::class)->icon('newspaper'),
          MenuItem::make('Свойства пакетов', AttributeResource::class)->icon('table-cells'),
//          ...parent::menu(),
//          MenuItem::make('Свойства пакетов', ProductAttributeResource::class),
          MenuGroup::make(static fn() => 'Настройки пользователей', [
            MenuItem::make(
              static fn() => 'Админы',
              MoonShineUserResource::class,
            ),
            MenuItem::make(
              static fn() => 'Роли',
              MoonShineUserRoleResource::class,
            ),
          ])->icon('adjustments-vertical'),
          MenuGroup::make(static fn() => 'Настройки меню', [
            MenuItem::make('Меню', MenuResource::class),
            MenuItem::make('Элементы меню', MenuItemResource::class),
          ])->icon('list-bullet'),
          MenuItem::make('Настройки сайта', SettingsPage::class)
                  ->icon('cog-6-tooth'),
        ];
    }

    /**
     * @param  ColorManager  $colorManager
     */
    protected function colors(ColorManagerContract $colorManager): void
    {
        parent::colors($colorManager);

        $colorManager->primary('#fd000');
    }

    public function build(): Layout
    {
        return parent::build();
    }
}
