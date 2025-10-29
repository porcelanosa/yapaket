@props(['color' => 'green'])

@php

       $colorMap = [
           'green' => 'bg-green-600 hover:bg-green-700 text-white',
           'red' => 'bg-red-600 hover:bg-red-700 text-white',
           'blue' => 'bg-blue-600 hover:bg-blue-700 text-white',
           'gray' => 'bg-gray-600 hover:bg-gray-700 text-white',
           'white' => 'bg-white hover:bg-gray-100 text-gray-900 border border-gray-300',
       ];

       $defaultColorClass = $colorMap[$color] ?? $colorMap['green'];

       // Объединяем: базовые + цвет + пользовательские классы
       $allClasses = implode(' ', [
           $baseClasses,
           $defaultColorClass,
           $attributes->get('class', ''),
       ]);

       $mergedAttributes = $attributes->merge(['class' => $allClasses]);
@endphp

@if($href)
    <a {{ $mergedAttributes }} href="{{ $href }}">
        {{ $slot }}
    </a>
@else
    <button {{ $mergedAttributes }} type="{{ $type }}">
        {{ $slot }}
    </button>
@endif