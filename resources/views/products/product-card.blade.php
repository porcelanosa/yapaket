@props(['category', 'product'])
<a href="{{ route('products.show', [$category, $product]) }}" class="card-in-list group">
    <div class="relative w-full h-48 bg-gray-200 flex items-center justify-center">
        <img src="{{ route('product-image.resize',
 ['imageId' => $product->primaryImage->id, 'width' => 80, 'height' => 60, 'type'=>'webp', 'quality'=>60]) }}"
             class="absolute w-full h-full object-cover blur-sm thumb" />
        <img src="{{ route('product-image.resize', ['imageId' => $product->primaryImage->id, 'width' => 300]) }}"
             loading="lazy"
             class="absolute w-full h-full object-cover main transition-transform duration-300 group-hover:scale-105 opacity-0"
             onload="this.style.opacity='1'; this.closest('div').querySelector('.thumb').style.display='none'" />
    </div>

    <div class="px-4 py-2">
        <h3 class="text-xl font-semibold text-gray-800">{{ $product->name }}</h3>
        <div class="flex justify-between">
            <p class="text-lg font-medium text-gray-600 mt-2">от
                {{--<x-svgicon name="ruble3" class="w-3 h-4 inline-block m-0"/>--}}
                {{ $product->price }}</p>
            <p class="text-lg font-medium text-gray-600 mt-2">от {{ $product->circulation }} шт.</p>
        </div>
    </div>
</a>