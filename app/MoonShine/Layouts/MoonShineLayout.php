<?php

declare(strict_types = 1);

namespace App\MoonShine\Layouts;

use App\MoonShine\Resources\AttributeResource;
use App\MoonShine\Resources\CategoryResource;
use App\MoonShine\Resources\PageResource;
use App\MoonShine\Resources\ProductAttributeResource;
use App\MoonShine\Resources\ProductResource;
use MoonShine\ColorManager\ColorManager;
use MoonShine\Contracts\ColorManager\ColorManagerContract;
use MoonShine\Laravel\Layouts\CompactLayout;
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
          MenuItem::make('Пакеты', ProductResource::class),
          MenuItem::make('Категории', CategoryResource::class),
          MenuItem::make('Страницы', PageResource::class),
          MenuItem::make('Свойства пакетов', AttributeResource::class),
          ...parent::menu(),
          MenuItem::make('ProductAttributes', ProductAttributeResource::class),

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
