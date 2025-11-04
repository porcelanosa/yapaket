<?php

namespace App\Providers;

use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Category;

class CategoryMenuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        ViewFacade::composer('categories.category-menu', function (View $view) {
            $menuCategories = Category::getTwoLevelMenu();
            $view->with('menuCategories', collect($menuCategories));
        });
    }
}
