@extends('layouts.mainLayout')

@section('title', 'Каталог')
@section('meta_description', 'Каталог продукции и услуг')

@section('content')
    <div class="container mx-auto px-4 py-8">

        <h1 class="text-3xl font-bold text-gray-900 mb-8">Каталог пакетов с логотипами.</h1>

        @if($categories->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($categories as $category)
                    <a href="{{ route('categories.show', $category) }}" class="group block bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                        <div class="relative">
                            {{-- Для категорий пока не предусмотрены изображения, используется плейсхолдер --}}
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l-1.586-1.586a2 2 0 00-2.828 0L6 14m6-6l.01.01"></path></svg>
                            </div>
                        </div>
                        <div class="p-4">
                            <h2 class="text-xl font-semibold text-gray-800 group-hover:text-red-600 transition-colors duration-300">{{ $category->name }}</h2>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <p class="text-gray-700">В данный момент в каталоге нет категорий.</p>
        @endif
    </div>
@endsection
