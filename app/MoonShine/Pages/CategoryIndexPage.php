<?php

declare(strict_types = 1);

namespace App\MoonShine\Pages;

use Leeto\MoonShineTree\View\Components\TreeComponent;
use MoonShine\Laravel\Pages\Crud\IndexPage;

class CategoryIndexPage extends IndexPage
{
    protected function mainLayer(): array
    {
        return [
          ...$this->getPageButtons(),
          TreeComponent::make($this->getResource()),
        ];
    }
}
