@extends('layouts.mainLayout')

@section('title', 'Поиск: ' . e($query))

@section('content')
    <div class="container mx-auto px-4">
        <h1 class="first-title">Результаты поиска</h1>

        @if($query)
            <p class="text-gray-500 mb-6">
                Вы искали: <span class="font-semibold text-black">"{{ $query }}"</span>
            </p>
        @endif

        @if($results->isEmpty())
            <p class="text-gray-600">Ничего не найдено 😕</p>
        @else
            <div class="space-y-6">
                @foreach($results as $item)
                    <div class="p-4 border border-gray-200 rounded-lg hover:shadow-md transition">
                        <a href="{{ $item['url'] }}" class="text-red-600 hover:underline text-xl font-semibold block mb-2">
                            {{ $item['title'] }}
                        </a>

                        <div class="text-gray-700 text-sm mb-2">
                            {!! nl2br(e($item['description'])) !!}
                        </div>

                        @if($item['type'] === 'product')
                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                                @if(!empty($item['price']))
                                    <span class="font-semibold text-black">Цена:</span> {{ $item['price'] }}
                                @endif
                                @if(!empty($item['circulation']))
                                    <span class="font-semibold text-black">Тираж:</span> {{ $item['circulation'] }}
                                @endif
                            </div>
                        @endif

                        <div class="mt-3 text-xs uppercase tracking-wide text-gray-400">
                            @if($item['type'] === 'product')
                                Товар
                            @elseif($item['type'] === 'page')
                                Страница
                            @elseif($item['type'] === 'post')
                                Пост
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
