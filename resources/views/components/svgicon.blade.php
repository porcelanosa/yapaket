@props(['name', 'class' => 'w-5 h-5'])

<svg {{ $attributes->merge(['class' => $class]) }}
     aria-hidden="true"
     preserveAspectRatio="xMidYMid meet"
     width="100%"
     height="100%">
    <use href="{{ '/build/sprite.svg#icon-' . $name }}" />
</svg>