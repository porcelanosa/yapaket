<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

final class TrixServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadViewsFrom(resource_path('views/fields/trix'),'porcelanosa-trix');


        $this->publishes([
            __DIR__ . '/../../public' => public_path('vendor/porcelanosa-trix'),
        ], ['porcelanosa-trix-assets', 'laravel-assets']);
    }
}
