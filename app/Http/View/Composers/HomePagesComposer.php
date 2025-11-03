<?php

declare(strict_types = 1);

namespace App\Http\View\Composers;

use App\Models\Category;
use App\Models\Page;
use Illuminate\Support\Facades\Request;
use Illuminate\View\View;

class HomePagesComposer
{
    public function compose(View $view): void
    {
        // Отключаем композер для всех /admin маршрутов
        if (Request::is('admin*') || Request::is('moonshine*')) {
            return;
        }
        $homePages = Page::inMain()->get();

        $view->with('homePages', $homePages);
    }
}