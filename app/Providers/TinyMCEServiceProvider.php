<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

final class TinyMCEServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadViewsFrom('../resources/views/fields/tinymce','porcelanosa-tinymce');


        $this->publishes([
            __DIR__ . '/../../public' => public_path('vendor/porcelanosa-tinymce'),
        ], ['porcelanosa-tinymce-assets', 'laravel-assets']);
    }
}
