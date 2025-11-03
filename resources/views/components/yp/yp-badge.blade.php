@props(['color' => 'gray', 'rounded' => 'sm'])

@php
    // Объединяем все классы: базовые + цвет + скругление + пользовательские
    $allClasses = implode(' ', [
        $baseClasses,
        $colorClasses,
        $roundedClasses,
        $attributes->get('class', ''),
    ]);

    $mergedAttributes = $attributes->merge(['class' => $allClasses]);
@endphp

<span {{ $mergedAttributes }}>
    {{ $slot }}
</span>
