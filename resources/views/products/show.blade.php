@php
    use Illuminate\Support\Facades\Storage;
@endphp

@extends('layouts.mainLayout')

@section('title', $product->title)
@section('meta_description', $product->meta_description)

@section('content')
    <div class="pb-6 px-0">
        <!-- Общая галерея для всех изображений -->
        <div id="post-gallery" class="pswp-gallery">
            <!-- Основное изображение и описание -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
                <div class="rounded-lg overflow-hidden shadow-md bg-white">
                    @if($primary_image)
                        <a href="{{ $primary_image->url }}"
                           data-pswp-width="{{ $primary_image->width ?? 1200 }}"
                           data-pswp-height="{{ $primary_image->height ?? 800 }}"
                           data-cropped="true"
                           target="_blank"
                           rel="noopener"
                           class="popup block w-full h-full relative">
                            <div class="absolute inset-0 bg-gray-200 animate-pulse"></div>
                            <img src="{{ route('product-image.resize', ['imageId' => $primary_image->id, 'width' => 456]) }}"
                                 alt="{{ $primary_image->title ?? 'Product image' }}"
                                 loading="lazy"
                                 class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                 onload="this.previousElementSibling.style.display='none'"
                            >
                            <div class="absolute inset-0 bg-transparent bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center">
                                <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"></path>
                                </svg>
                            </div>
                        </a>
                    @endif
                </div>

                <!-- Описание продукта -->
                <div class="space-y-6">
                    <h1 class="text-3xl font-bold text-gray-900">{{$product->name}}</h1>
                    <p class="text-lg text-gray-700">
                        {{$product->short_description}}
                    </p>
                    <h3 class="text-xl font-semibold text-gray-900">Цена от {{$product->price}} руб</h3>
                    <p class="text-lg font-medium text-gray-900">Тираж от {{$product->circulation}} экз</p>

                                        <x-yp.yp-input type="text" placeholder="введите имя" name="fio"/>

                                        <x-umbra-ui::button class="bg-green-600 hover:bg-green-700 text-white">
                                            <x-umbra-ui::icons.shopping-cart-plus class="text-red-500" />&nbsp;Primary Button
                                        </x-umbra-ui::button>
                                        <x-umbra-ui::button class="bg-black hover:bg-gray-950 text-white">Custom Button</x-umbra-ui::button>

                                         Form Inputs
                                        <x-umbra-ui::input type="email" placeholder="Enter email" />
                                        <x-umbra-ui::textarea placeholder="Your message..." />
                                        <x-umbra-ui::select>
                                            <option>Choose...</option>
                                            <option value="1">Option 1</option>
                                        </x-umbra-ui::select>

                                         Form Controls
                                        <x-umbra-ui::checkbox id="terms" />
                                        <x-umbra-ui::label for="terms">Accept Terms</x-umbra-ui::label>
                    {{-- Кнопка открытия --}}
                    <div x-data>
                        <x-yp.yp-button
                                x-on:click="console.log('click'); $dispatch('open-modal', 'order-modal')"
                                color="green">
                            <x-umbra-ui::icons.shopping-cart-plus class="text-white" />
                            &nbsp;Заказать
                        </x-yp.yp-button>
                    </div>
                    {{-- Модалка --}}
                    <x-yp.yp-modal id="order-modal" title="Заказать {{$product->name}}">
                        <p>Содержимое модалки</p>
                        <x-umbra-ui::input type="email" placeholder="Enter email" />
                        <x-umbra-ui::textarea placeholder="Your message..." />
                        <x-umbra-ui::select>
                            <option>Choose...</option>
                            <option value="1">Option 1</option>
                        </x-umbra-ui::select>

                        Form Controls
                        <x-umbra-ui::checkbox id="terms" />
                        <x-umbra-ui::label for="terms">Accept Terms</x-umbra-ui::label>
                        <x-slot:footer>
                            <x-yp.yp-button x-on:click="$dispatch('close-modal', 'my-modal')">
                                Закрыть
                            </x-yp.yp-button>
                        </x-slot:footer>
                    </x-yp.yp-modal>
                </div>
            </div>

            <!-- Галерея изображений (все изображения, включая основное) -->
            @if($product_images && $product_images->count() > 0)
                <div class="mb-10">
                    <h2 class="text-2xl font-bold mb-6 text-gray-900">Примеры работ</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($product_images as $image)
                            <div class="group relative rounded-lg overflow-hidden shadow-md bg-white aspect-square">
                                <a href="{{ $image->url }}"
                                   data-pswp-width="{{ $image->width ?? 1200 }}"
                                   data-pswp-height="{{ $image->height ?? 800 }}"
                                   data-cropped="true"
                                   target="_blank"
                                   rel="noopener"
                                   class="popup block w-full h-full">
                                    <div class="absolute inset-0 bg-gray-200 animate-pulse"></div>
                                    <img src="{{ route('product-image.resize', ['imageId' => $image->id, 'width' => 300]) }}"
                                         alt="{{ $image->title ?? 'Product image' }}"
                                         loading="lazy"
                                         class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                         onload="this.previousElementSibling.style.display='none'"
                                    >
                                    <div class="absolute inset-0 bg-transparent bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"></path>
                                        </svg>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Остальной контент (технические характеристики и т.д.) -->
        <!-- Технические характеристики -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
            @foreach($product->attributes() as $attr)
                <div>
                    <h3 class="text-xl font-semibold mb-4 text-gray-900">Срок изготовления</h3>
                    <p class="text-lg mb-2">от 5 рабочих дней</p>
                    <h3 class="text-xl font-semibold mb-4 mt-6 text-gray-900">Минимальный тираж</h3>
                    <p class="text-lg">100 шт.</p>
                </div>
            @endforeach
            <div>
                <h3 class="text-xl font-semibold mb-4 text-gray-900">Материал</h3>
                <p class="text-lg mb-2">Любой</p>
                <h3 class="text-xl font-semibold mb-4 text-gray-900">Цвет</h3>
                <p class="text-lg mb-2">Любой</p>
                <h3 class="text-xl font-semibold mb-4 text-gray-900">Размер</h3>
                <p class="text-lg">Любой</p>
            </div>
        </div>

        <!-- Ценовая информация -->
        <div class="mb-10 p-6 bg-yellow-50 border-l-4 border-yellow-500">
            <h3 class="text-xl font-bold text-yellow-800 mb-3">МИНИМАЛЬНАЯ СТОИМОСТЬ ЗАКАЗА: 7000 рублей</h3>
            <p class="text-red-600 font-bold mb-2">Внимание! Все картинки креативных визитных карточек представленные на
                этой странице по стоимости превышают 7000 руб. при тираже 100 экземпляров.</p>
            <p class="text-red-600 font-bold mb-2">Дорогие материалы под визиточки для важных персон:</p>
            <ul class="list-disc list-inside space-y-1 text-gray-800">
                <li>Цветные в массе дизайнерские картоны;</li>
                <li>Металл;</li>
                <li>Пластик;</li>
                <li>Дерево;</li>
                <li>Кожа;</li>
            </ul>
        </div>

        <!-- Советы по созданию люкса -->
        <div class="mb-10">
            <h3 class="text-xl font-bold mb-4 text-gray-900">Лучшие 11 способов из рыночной визитки сделать креативную
                класса люкс:</h3>
            <ol class="list-decimal list-inside space-y-2 text-gray-800">
                <li>Элитный материал для нанесения;</li>
                <li>Тиснение логотипа;</li>
                <li>Конгрев символики;</li>
                <li>Вырубка необычной формы;</li>
                <li>Термоподъем фамилии;</li>
                <li>Золотой срез;</li>
                <li>Кашировка материалов;</li>
            </ol>
        </div>

        <!-- Дополнительная информация -->
        <div class="mb-10 p-6 bg-gray-50 rounded-lg">
            <h3 class="text-xl font-bold mb-4 text-gray-900">НЕМНОГО про креативные визитки:</h3>
            <p class="text-gray-800 leading-relaxed">
                Трудно представить солидного, делового человека без визитной карточки, а если еще этот человек занимает
                высокий пост, то соответственно и его визитная карточка должна быть класса люкс. Мы предлагаем VIP
                визитки, изготовленные из различных материалов: визитки с тиснением и конгревом, с УФ лаком и
                кашировкой. Дизайнерские визитки могут быть совершенно необычными, в том числе и прозрачные из пластика.
                Если Вам нужны необычные, креативные визитки, то в компании yourprint Вы можете заказать всё то, что
                пожелаете.
            </p>
        </div>

        <!-- Похожие товары -->
        <div class="mb-10">
            <h2 class="text-2xl font-bold mb-6 text-gray-900">Похожие товары</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Товар 1 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="{{ asset('images/similar/discount-cards.jpg') }}" alt="Дисконтные карточки"
                         class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="text-xl font-semibold mb-2 text-gray-900">Дисконтные карточки</h3>
                        <p class="text-gray-700 mb-4">Печать дисконтных пластиковых карточек на заказ. Изготовление
                            скидочных карт. Производство бонусных пластиковых карт.</p>
                        <p class="text-lg font-bold text-gray-900">Цена<br>6000 руб</p>
                    </div>
                </div>

                <!-- Товар 2 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="{{ asset('images/similar/economy-cards.jpg') }}" alt="Визитки Эконом класса"
                         class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="text-xl font-semibold mb-2 text-gray-900">Визитки Эконом класса</h3>
                        <p class="text-gray-700 mb-4">Печать визитных карточек по эконом цене на недорогих сортах
                            бумаги.</p>
                        <p class="text-lg font-bold text-gray-900">Цена<br>3000 руб</p>
                    </div>
                </div>

                <!-- Товар 3 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="{{ asset('images/similar/brochures.jpg') }}" alt="Брошюры на скрепке"
                         class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="text-xl font-semibold mb-2 text-gray-900">Брошюры на скрепке</h3>
                        <p class="text-gray-700 mb-4">Печать цветных брошюр в типографии. Производство каталогов с
                            описанием товаров на заказ. Изготовление рекламных буклетов для выставки.</p>
                        <p class="text-lg font-bold text-gray-900">Цена<br>10000 руб</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    @vite('resources/js/post-gallery.js')
@endpush