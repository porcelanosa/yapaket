<?php

namespace App\Http\Controllers;

use App\Helpers\BreadcrumbHelper;
use App\Helpers\ProductImageHelper;
use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Список всех товаров (можно адаптировать под общую страницу каталога)
     */
    public function index()
    {
        $products = Product::with('categories', 'primaryImage')->paginate(20);

        $breadcrumbs = new BreadcrumbHelper()
            ->add('Все товары', route('products.index'))
            ->get();

        return view('products.index', [
          'products'    => $products,
          'breadcrumbs' => $breadcrumbs,
        ]);
    }

    /**
     * Показать карточку товара
     */
    public function show(?Category $category, Product $product)
    {
        if ($product->productImages) {
            $product = ProductImageHelper::setImagesDimensions($product);
        }

        $breadcrumbs = new BreadcrumbHelper()
            ->forProduct($product, $category)
            ->get();

        return view('products.show', [
          'product'        => $product,
          'category'       => $category,
          'breadcrumbs'    => $breadcrumbs,
          'primary_image'  => $product->primaryImage ?? null,
          'product_images' => $product->productImages ?? collect(),
        ]);
    }
}
