<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Services\SiteSettingsService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
    public function boot(SiteSettingsService $settings): void
    {
        // Устанавливаем путь к public директории
        $publicPath = config('app.public_path', 'public');

        if ($publicPath !== 'public') {
            $fullPath = base_path($publicPath);
            if (is_dir($fullPath)) {
                $this->app->usePublicPath($fullPath);
            }
        }

        View::composer('*', function ($view) use ($settings) {
            $view->with('contact', $settings->getContactInfo());
            $view->with('mainNav', $settings->getMainNav());
            $view->with('socials', $settings->getSocials());
            $view->with('menuCategories2', $settings->getMenuCategories2());
            $view->with('mainSections', $settings->getMainSections());
            $view->with('newsArticles', $settings->getNewsArticles());
            $view->with('interestingOffers', $settings->getInterestingOffers());
        });
    }
}
