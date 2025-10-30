<?php

declare(strict_types = 1);

namespace App\Providers;

use App\MoonShine\Pages\SettingsPage;
use App\MoonShine\Resources\AttributeResource;
use App\MoonShine\Resources\CategoryResource;
use App\MoonShine\Resources\ImageResource;
use App\MoonShine\Resources\MenuItemResource;
use App\MoonShine\Resources\MenuResource;
use App\MoonShine\Resources\MoonShineUserResource;
use App\MoonShine\Resources\MoonShineUserRoleResource;
use App\MoonShine\Resources\PageResource;
use App\MoonShine\Resources\PostResource;
use App\MoonShine\Resources\ProductAttributeResource;
use App\MoonShine\Resources\ProductImageResource;
use App\MoonShine\Resources\ProductResource;
use Illuminate\Support\ServiceProvider;
use MoonShine\Contracts\Core\DependencyInjection\ConfiguratorContract;
use MoonShine\Contracts\Core\DependencyInjection\CoreContract;
use MoonShine\Laravel\DependencyInjection\MoonShine;
use MoonShine\Laravel\DependencyInjection\MoonShineConfigurator;

class MoonShineServiceProvider extends ServiceProvider
{
    /**
     * @param  MoonShine              $core
     * @param  MoonShineConfigurator  $config
     *
     */
    public function boot(CoreContract $core, ConfiguratorContract $config): void
    {
        $core
          ->resources([
            MoonShineUserResource::class,
            MoonShineUserRoleResource::class,
            AttributeResource::class,
            ProductAttributeResource::class,
            ImageResource::class,
            ProductImageResource::class,
            ProductResource::class,
            PageResource::class,
            CategoryResource::class,
            PostResource::class,
            MenuResource::class,
            MenuItemResource::class
          ])
          ->pages([
            ...$config->getPages(),
            SettingsPage::class,
          ]);
    }
}
