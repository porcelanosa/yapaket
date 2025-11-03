<?php

namespace App\Http\View\Composers;

use App\Services\MenuService;
use Illuminate\Support\Facades\Request;
use Illuminate\View\View;

class MenuComposer
{
    protected MenuService $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    public function compose(View $view): void
    {
        // Отключаем композер для всех /admin маршрутов
        // Убрал - проверяется во ViewServiceProvider
//        if (Request::is('admin*') || Request::is('moonshine*')) {
//            return;
//        }

        // Загружаем меню с использованием runtime cache
        // При повторных обращениях будет использоваться кеш из памяти
        $headerMenu = $this->menuService->getMenuTree('header-menu');
        $footerMenu = $this->menuService->getMenuTree('footer-menu');

        $view->with('headerMenu', $headerMenu);
        $view->with('footerMenu', $footerMenu);
    }
}
