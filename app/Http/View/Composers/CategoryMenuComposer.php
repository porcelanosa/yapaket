<?php

declare(strict_types = 1);

namespace App\Http\View\Composers;

use App\Models\Category;
use Illuminate\View\View;

class CategoryMenuComposer
{
    public function compose(View $view): void
    {
        $categoryMenu = Category::getTwoLevelMenu();

        $view->with('categoryMenu', $categoryMenu);
    }
}