<div class="sidebar">
    @if($menuCategories->isNotEmpty())
        @foreach($menuCategories as $category)
            <div class="mb-6">
                <h3 class="pb-1 mb-3 mt-1">
                    <a href="{{ $category['url'] }}" class="text-red-600 hover:text-red-700">
                        {{ $category['name'] }}
                    </a>
                </h3>
                <ul class="space-y-2 text-sm">
                    @foreach($category['children'] as $item)
                        <li>
                            <a href="{{ $item['url'] }}" class="text-gray-700 hover:text-red-600">
                                {{ $item['name'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    @endif
</div>