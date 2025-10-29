<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

final class CKEditorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadViewsFrom('../resources/views/fields/ckeditor', 'porcelanosa-ckeditor');


        $this->publishes([
            __DIR__ . '/../../public' => public_path('vendor/porcelanosa-ckeditor'),
        ], ['porcelanosa-ckeditor-assets', 'laravel-assets']);
    }
}
