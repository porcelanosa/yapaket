@if($items && count($items)>0)
    <div>
        <h3 class="text-lg font-semibold mb-4">{{ $items[0]->menu->title }}</h3>
        <ul class="space-y-2 text-sm text-gray-300">
            @foreach ($items as $item)
                {{-- You can add classes for active links, dropdowns etc. --}}
                <li class="{{ !empty($item->children) ? 'has-children' : '' }}">
                    <a href="{{ $item->uurl }}" class="text-gray-300 hover:text-white no-underline">{{ $item->title }}</a>
                    @if (!empty($item->children))
                        <ul class="submenu">
                            @include('partials.menu', ['items' => $item->children])
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
@endif