<?php

namespace App\Providers;

use App\Http\View\Composers\MenuComposer;
use App\View\Composers\SettingsComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Using a wildcard '*' makes the composed variables available in all views.
        // This is the most flexible approach for shared elements like menus.
        View::composer('*', MenuComposer::class);
        
        // Добавляем настройки сайта только для фронтенда (исключаем админку)
        View::composer(['*'], function($view) {
            // Проверяем, что это не админка MoonShine
            if (request()->is('admin/*') || request()->is('moonshine/*')) {
                return;
            }
            
            // Применяем SettingsComposer только для фронтенда
            app(SettingsComposer::class)->compose($view);
        });
    }
}
