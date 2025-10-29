@php
    use Illuminate\Support\Facades\Storage;
@endphp
@extends('layouts.mainLayout')

@section('title', $category->title)
@section('meta_description', $category->meta_description ?? 'Продукция и услуги в категории ' . $category->name)

@section('content')
    <div class="container mx-auto px-4 py-8">

        <h1 class="text-3xl font-bold text-gray-900 mb-8">{{ $category->name }}</h1>
        @if($image_url)
            <img src="{{ $image_url }}"
                 alt="{{ $category->title ?? 'Product image' }}"
                 loading="lazy"
                 class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                 onload="this.previousElementSibling.style.display='none'" />
        @endif
        {{--        >--}}

        <img src="{{ $thumb_url }}" />
        {{-- Display Subcategories --}}
        @if($category->children->isNotEmpty())
            <section class="max-w-7xl mx-auto px-0 py-12 ">
                {{--<h2 class="text-2xl font-semibold text-gray-800 mb-6">Подкатегории</h2>--}}

                <div class="@container/cards w-full">
                    <div class="grid grid-cols-1 @sm/cards:grid-cols-1 @xl/cards:grid-cols-2 @4xl/cards:grid-cols-3 gap-6">
                        @foreach($category->children as $child)
                            <a href="{{ route('categories.show', $child) }}"
                               class="group block bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden flex flex-col h-full">

                                <!-- Фото -->
                                <div class="relative w-full aspect-[4/3] bg-gray-100 flex items-center justify-center overflow-hidden">

                                    @if($child->getFirstMediaUrl('category_image', 'category_thumb') ?? false)
                                        <img src="{{ $child->getFirstMediaUrl('category_image', 'category_thumb') }}"
                                             alt="{{ $child->name }}"
                                             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                    @else
                                        <x-svgicon name="bags" class="w-32 h-32 "/>
                                    @endif
                                </div>

                                <!-- Контент -->
                                {{--                                <div class="p-4 flex-1 flex items-center justify-center text-center">
                                                                    <h3 class="text-lg font-semibold text-gray-800 group-hover:text-red-600 transition-colors">
                                                                        {{ $child->name }}
                                                                    </h3>
                                                                </div>--}}
                                <div class="p-6 flex flex-col justify-between flex-1">
                                    <div>
                                        <h3 class="text-xl font-semibold text-gray-900 group-hover:text-red-600 transition-colors">
                                            {{ $child->name }}
                                        </h3>
{{--                                        <p class="text-gray-500 mb-2">{{ $child->title }}</p>--}}
                                        <p class="text-gray-600">{{ $child->short_description }}</p>
                                    </div>
                                    <div class="flex space-x-4 mt-4 text-gray-500">
                                        <span class="flex items-center gap-1">
                                            <x-umbra-ui::icons.folder-open class="w-4 h-4" />
                                            Продуктов: {{ count($child->products) }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <section class="@container/teams max-w-7xl mx-auto px-0 py-12">
            <div class="w-full">
                <div class="grid @md/teams:grid-cols-1 @xl/teams:grid-cols-2 @4xl/teams:grid-cols-4 gap-6">

                    <!-- Карточка -->
                    <div class="relative group bg-white rounded-2xl shadow-sm overflow-hidden flex flex-col h-full sm:min-h-64">
                        <!-- Ссылка на всю карточку -->
                        <a href="#" class="absolute inset-0 z-10"></a>

                        <!-- Фото -->
                        <img class="w-full sm:w-56 h-64 sm:h-auto object-cover flex-shrink-0 transition-transform duration-300 group-hover:scale-105"
                             src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/bonnie-green.png"
                             alt="Bonnie Green">

                        <!-- Контент -->
                        <div class="p-6 flex flex-col justify-between flex-1">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">
                                    Bonnie Green
                                </h3>
                                <p class="text-gray-500 mb-2">CEO & Web Developer</p>
                                <p class="text-gray-600">Bonnie drives the technical strategy of the flowbite platform
                                    and brand.</p>
                            </div>
                            <div class="flex space-x-4 mt-4 text-gray-500">
                                <a href="#"><i class="fa-brands fa-facebook"></i></a>
                                <a href="#"><i class="fa-brands fa-twitter"></i></a>
                                <a href="#"><i class="fa-brands fa-github"></i></a>
                                <a href="#"><i class="fa-solid fa-basketball"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- Карточка -->
                    <div class="relative group bg-white rounded-2xl shadow-sm overflow-hidden flex flex-col h-full sm:min-h-64">
                        <a href="#" class="absolute inset-0 z-10"></a>
                        <img class="w-full sm:w-56 h-64 sm:h-auto object-cover flex-shrink-0 transition-transform duration-300 group-hover:scale-105"
                             src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/jese-leos.png"
                             alt="Jese Leos">
                        <div class="p-6 flex flex-col justify-between flex-1">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">
                                    Jese Leos
                                </h3>
                                <p class="text-gray-500 mb-2">CTO</p>
                                <p class="text-gray-600">Jese drives the technical strategy of the flowbite platform and
                                    brand.</p>
                            </div>
                            <div class="flex space-x-4 mt-4 text-gray-500">
                                <a href="#"><i class="fa-brands fa-facebook"></i></a>
                                <a href="#"><i class="fa-brands fa-twitter"></i></a>
                                <a href="#"><i class="fa-brands fa-github"></i></a>
                                <a href="#"><i class="fa-solid fa-basketball"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- Карточка -->
                    <div class="relative group bg-white rounded-2xl shadow-sm overflow-hidden flex flex-col h-full sm:min-h-64">
                        <a href="#" class="absolute inset-0 z-10"></a>
                        <img class="w-full sm:w-56 h-64 sm:h-auto object-cover flex-shrink-0 transition-transform duration-300 group-hover:scale-105"
                             src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/michael-gough.png"
                             alt="Michael Gough">
                        <div class="p-6 flex flex-col justify-between flex-1">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">
                                    Michael Gough
                                </h3>
                                <p class="text-gray-500 mb-2">Senior Front-end Developer</p>
                                <p class="text-gray-600">Michael drives the technical strategy of the flowbite platform
                                    and brand.</p>
                            </div>
                            <div class="flex space-x-4 mt-4 text-gray-500">
                                <a href="#"><i class="fa-brands fa-facebook"></i></a>
                                <a href="#"><i class="fa-brands fa-twitter"></i></a>
                                <a href="#"><i class="fa-brands fa-github"></i></a>
                                <a href="#"><i class="fa-solid fa-basketball"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- Карточка -->
                    <div class="relative group bg-white rounded-2xl shadow-sm overflow-hidden flex flex-col h-full sm:min-h-64">
                        <a href="#" class="absolute inset-0 z-10"></a>
                        <img class="w-full sm:w-56 h-64 sm:h-auto object-cover flex-shrink-0 transition-transform duration-300 group-hover:scale-105"
                             src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/lana-byrd.png"
                             alt="Lana Byrd">
                        <div class="p-6 flex flex-col justify-between flex-1">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">
                                    Lana Byrd
                                </h3>
                                <p class="text-gray-500 mb-2">Marketing & Sale</p>
                                <p class="text-gray-600">Lana drives the technical strategy of the flowbite platform and
                                    brand.</p>
                            </div>
                            <div class="flex space-x-4 mt-4 text-gray-500">
                                <a href="#"><i class="fa-brands fa-facebook"></i></a>
                                <a href="#"><i class="fa-brands fa-twitter"></i></a>
                                <a href="#"><i class="fa-brands fa-github"></i></a>
                                <a href="#"><i class="fa-solid fa-basketball"></i></a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>


        {{-- Display Products in Category --}}
        @if($category->products->isNotEmpty())
            <div>
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Товары в категории</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($category->products as $product)
                        <a href="{{ route('products.show', [$category, $product]) }}"
                           class="group block bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                            <div class="relative">
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l-1.586-1.586a2 2 0 00-2.828 0L6 14m6-6l.01.01"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="p-4">
                                <h3 class="text-xl font-semibold text-gray-800 group-hover:text-red-600 transition-colors duration-300">{{ $product->name }}</h3>
                                <p class="text-gray-600 mt-2">от {{ $product->price }} руб.</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        @if($category->children->isEmpty() && $category->products->isEmpty())
            <p class="text-gray-700">В этой категории пока нет подкатегорий или товаров.</p>
        @endif
    </div>
@endsection
