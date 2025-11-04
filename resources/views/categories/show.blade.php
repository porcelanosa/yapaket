@php
    use Illuminate\Support\Facades\Storage;
@endphp
@extends('layouts.mainLayout')

@section('title', $category->title)
@section('meta_description', $category->meta_description ?? 'Продукция и услуги в категории ' . $category->name)

@section('content')
    <div class="container mx-auto px-4 pb-8">
        <h1 class="first-title">{{ $category->name }}</h1>
        @if($image_url)
            <div class="relative w-full aspect-[4/3] bg-gray-100 flex items-center justify-center overflow-hidden">
                <img src="{{ $thumb_url }}" alt="{{ $category->title ?? 'Product image' }}"
                     id="thumb-{{ $category->id }}"
                     class="absolute inset-0 w-full h-full object-cover blur-sm z-10 transition-opacity" />

                <img src="{{ $image_url }}"
                     alt="{{ $category->title ?? 'Product image' }}"
                     loading="lazy"
                     class="absolute inset-0 w-full h-full object-cover z-0 opacity-0 transition-opacity"
                     onload="this.style.opacity='1';
                     this.previousElementSibling.style.display='none'" />
            </div>
        @endif
        @if($category->children->isNotEmpty())
            <section class="max-w-7xl mx-auto px-0 py-12 ">
                <div class="@container/cards w-full">
                    <div class="grid grid-cols-1 @sm/cards:grid-cols-1 @xl/cards:grid-cols-2 @4xl/cards:grid-cols-3 gap-6">
                        @foreach($category->children as $child)
                            <x-category-card :category="$child" />
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        @if($category->products->isNotEmpty())
            <div>
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Товары в категории</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($category->products as $product)
                        <x-product-card :category="$category" :product="$product" />
                    @endforeach
                </div>
            </div>
        @endif

        @if($category->children->isEmpty() && $category->products->isEmpty())
            <p class="text-gray-700">В этой категории пока нет подкатегорий или товаров.</p>
        @endif
    </div>
@endsection
