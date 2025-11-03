<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = trim($request->get('q', ''));

        if ($query==='') {
            return view('search.index', [
              'query'   => '',
              'results' => collect(),
            ]);
        }

        // === Поиск по продуктам ===
        $products = Product::active()
                           ->where(function ($q) use ($query) {
                               $q
                                 ->where('name', 'like', "%{$query}%")
                                 ->orWhere('title', 'like', "%{$query}%")
                                 ->orWhere('short_description', 'like', "%{$query}%")
                                 ->orWhere('description', 'like', "%{$query}%");
                           })
                           ->limit(20)
                           ->get()
                           ->map(fn($product)
                               => [
                             'type'        => 'product',
                             'title'       => $product->title ?? $product->name,
                             'description' => $product->short_description,
                             'url'         => $product->url,
                             'price'       => $product->price,
                             'circulation' => $product->circulation,
                           ]);

        // === Поиск по страницам ===
        $pages = Page::active()
                     ->where(function ($q) use ($query) {
                         $q
                           ->where('name', 'like', "%{$query}%")
                           ->orWhere('title', 'like', "%{$query}%")
                           ->orWhere('short_description', 'like', "%{$query}%")
                           ->orWhere('description', 'like', "%{$query}%");
                     })
                     ->limit(20)
                     ->get()
                     ->map(fn($page)
                         => [
                       'type'        => 'page',
                       'title'       => $page->title ?? $page->name,
                       'description' => $page->short_description,
                       'url'         => $page->url,
                     ]);

        // === Поиск по постам ===
        $posts = Post::active()
                     ->where(function ($q) use ($query) {
                         $q
                           ->where('name', 'like', "%{$query}%")
                           ->orWhere('title', 'like', "%{$query}%")
                           ->orWhere('announce', 'like', "%{$query}%")
                           ->orWhere('content', 'like', "%{$query}%");
                     })
                     ->limit(20)
                     ->get()
                     ->map(fn($post)
                         => [
                       'type'        => 'post',
                       'title'       => $post->title ?? $post->name,
                       'description' => $post->announce,
                       'url'         => $post->url,
                     ]);

        // === Объединение результатов ===
        $results = $products
          ->concat($pages)
          ->concat($posts)
          ->sortBy('title', SORT_NATURAL | SORT_FLAG_CASE)
          ->values();

        return view('search.index', [
          'query'   => $query,
          'results' => $results,
        ]);
    }
}
