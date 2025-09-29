{{-- resources/views/design.blade.php --}}
@extends('layouts.mainLayout')

@section('content')

    {{-- Основные секции --}}
    @foreach($mainSections as $section)
{{--        <section class="bg-white rounded-lg p-6 mb-6">--}}
{{--            <h2 class="text-2xl font-bold text-red-600 border-b border-red-600 pb-2 mb-6">--}}
{{--                {{ $section['title'] }}--}}
{{--            </h2>--}}
{{--            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">--}}
{{--                @foreach($section['items'] as $item)--}}
{{--                    <div class="text-center">--}}
{{--                        <div class="w-16 h-16 bg-gray-200 rounded mx-auto mb-2"></div>--}}
{{--                        <p class="text-sm">{{ $item['title'] }}</p>--}}
{{--                    </div>--}}
{{--                @endforeach--}}
{{--            </div>--}}
{{--        </section>--}}
        <section class="bg-white rounded-lg p-6">
            <div class="flex flex-col md:flex-row gap-6">
                <div class="md:w-1/2">
                    <img src="{{ $section['image'] }}" alt="Printing equipment" class="w-full h-auto rounded">
                </div>
                <div class="md:w-1/2">
                    <h2 class="text-2xl font-bold text-red-600 mb-4">{{ $section['title'] }}</h2>
                    <p class="text-gray-600 leading-relaxed">
                        {{ $section['description'] }}
                    </p>
                </div>
            </div>
        </section>
    @endforeach

    {{-- Новости --}}
    <section class="bg-white rounded-lg p-6">
        <h2 class="text-2xl font-bold text-red-600 border-b border-red-600 pb-2 mb-6">
            Новости мира полиграфии
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($newsArticles as $article)
                <div>
                    <h3 class="font-bold mb-2">{{ $article['title'] }}</h3>
                    <p class="text-sm text-gray-600">{{ $article['content'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

@endsection
