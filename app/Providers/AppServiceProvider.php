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
