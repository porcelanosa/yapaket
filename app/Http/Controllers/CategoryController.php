<?php

declare(strict_types = 1);
namespace App\Http\Controllers;

use App\Helpers\BreadcrumbHelper;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Список всех категорий (главная страница каталога)
     */
    public function index()
    {
        $categories = Category::active()
                              ->whereNull('parent_id')
                              ->with(['children', 'products', 'products.primaryImage'])
                              ->orderBy('sort')
                              ->get();

        $breadcrumbs = new BreadcrumbHelper()
          ->add('Каталог', route('categories.index'))
          ->get();

        return view('categories.index', [
          'categories'  => $categories,
          'breadcrumbs' => $breadcrumbs,
        ]);
    }

    /**
     * Показать категорию и её подкатегории/товары
     */
    public function show(Category $category)
    {
        // load() больше не нужен - связи загружаются через resolveRouteBinding()
        $category->load(['children', 'products', 'products.primaryImage', 'children.products', 'children.products.primaryImage']);

        $breadcrumbs = new BreadcrumbHelper()
          ->forCategory($category)
          ->get();

        $image     = $category->getMedia('category_image')[0] ?? '';
        $thumb_url = $category->getFirstMediaUrl('category_image', 'category_thumb');

        return view('categories.show', [
          'category'    => $category,
          'image_url'   => $image ? $image->getUrl() : null,
          'thumb_url'   => $thumb_url,
          'breadcrumbs' => $breadcrumbs,
        ]);
    }
}
