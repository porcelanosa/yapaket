<?php

namespace App\Providers;

use App\Http\View\Composers\MenuComposer;
use App\Models\Page;
use App\View\Composers\SettingsComposer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
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
        // Пропускаем выполнение, если это консоль или админ-зона
        if (app()->runningInConsole() || Request::is('admin*') || Request::is('moonshine*')) {
            return;
        }

        $homePages = Cache::store('file')->remember('home_pages', now()->addMinutes(360), function () {
            return Page::inMain()->get();
        });

        View::share('homePages', $homePages);
        View::composer('*', SettingsComposer::class);
        View::composer('*', MenuComposer::class);
        View::composer('pages.show', function ($view) {
            /** @var Page $page */
            $page = $view->getData()['page'] ?? null;
            if ($page && $page->component) {
                $view->with('component', $page->component);
            }
        });
    }
}
