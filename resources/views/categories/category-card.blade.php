@props(['category'])

<a href="{{ route('categories.show', $category) }}" class="card-in-list group">
    <div class="relative w-full aspect-[4/3] bg-gray-100 flex items-center justify-center overflow-hidden">
        @if($category->getFirstMediaUrl('category_image', 'category_thumb') ?? false)
            <img src="{{ $category->getFirstMediaUrl('category_image', 'category_thumb') }}"
                 alt="{{ $category->name }}"
                 class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
        @else
            <x-svgicon name="bags" class="w-24 h-24 text-gray-400" />
        @endif
    </div>

    <div class="p-6 flex flex-col justify-between flex-1">
        <div>
            <h3 class="text-xl font-semibold text-gray-900 transition-colors">
                {{ $category->name }}
            </h3>
            @if($category->short_description)
                <p class="text-gray-600 mt-2">
                    {{ $category->short_description }}
                </p>
            @endif
        </div>

        <div class="flex space-x-4 mt-4 text-gray-500 text-sm">
            <span class="flex items-center gap-1">
                <x-umbra-ui::icons.folder-open class="w-4 h-4" />
                Продуктов: {{ $category->products_count ?? $category->products?->count() ?? 0 }}
            </span>
        </div>
    </div>
</a>
