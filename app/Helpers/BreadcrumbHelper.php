<?php

namespace App\Helpers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Collection;

class BreadcrumbHelper
{
    private Collection $breadcrumbs;

    public function __construct()
    {
        $this->breadcrumbs = collect();
        $this->add('home', route('home'));
    }

    /**
     * Добавить звено в цепочку.
     */
    public function add(string $title, ?string $url = null): self
    {
        $this->breadcrumbs->push([
            'title' => $title,
            'url'   => $url,
        ]);

        return $this;
    }

    /**
     * Сгенерировать цепочку для категории.
     */
    public function forCategory(Category $category): self
    {
        $parents = collect();
        $current = $category;

        // Собираем цепочку родительских категорий
        while ($current) {
            $parents->prepend($current);
            $current = $current->parent;
        }

        // Добавляем каждую категорию в хлебные крошки
        foreach ($parents as $parentCategory) {
            $this->add($parentCategory->name, route('categories.show', $parentCategory->slug));
        }

        return $this;
    }

    /**
     * Сгенерировать цепочку для продукта.
     */
    public function forProduct(Product $product, ?Category $category = null): self
    {
        // Если категория не пришла из URL, берем первую из связей продукта.
        // Это запасной вариант, т.к. основная категория должна быть в URL.
        $category = $category ?? $product->categories()->first();

        if ($category) {
            $this->forCategory($category);
        }

        // Добавляем сам продукт как последнее звено
        $this->add($product->name, route('products.show', ['category' => $category?->slug, 'product' => $product->slug]));

        return $this;
    }

    /**
     * Получить итоговую коллекцию хлебных крошек.
     */
    public function get(): Collection
    {
        // У последнего элемента не должно быть ссылки
        if ($this->breadcrumbs->isNotEmpty()) {
            $last = $this->breadcrumbs->pop();
            $last['url'] = null;
            $this->breadcrumbs->push($last);
        }

        return $this->breadcrumbs;
    }
}
