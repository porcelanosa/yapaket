<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use MoonShine\Contracts\Core\DependencyInjection\ConfiguratorContract;
use MoonShine\Contracts\Core\DependencyInjection\CoreContract;
use MoonShine\Laravel\DependencyInjection\MoonShine;
use MoonShine\Laravel\DependencyInjection\MoonShineConfigurator;
use App\MoonShine\Resources\MoonShineUserResource;
use App\MoonShine\Resources\MoonShineUserRoleResource;
use App\MoonShine\Resources\AttributeResource;
use App\MoonShine\Resources\ProductAttributeResource;
use App\MoonShine\Resources\ProductResource;
use App\MoonShine\Resources\PageResource;
use App\MoonShine\Resources\CategoryResource;

class MoonShineServiceProvider extends ServiceProvider
{
    /**
     * @param  MoonShine  $core
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
                ProductResource::class,
                PageResource::class,
                CategoryResource::class,
            ])
            ->pages([
                ...$config->getPages(),
            ])
        ;
    }
}
