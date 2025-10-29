@foreach ($items as $item)
    {{-- You can add classes for active links, dropdowns etc. --}}
    <li class="{{ !empty($item->children) ? 'has-children' : '' }}">
        <a href="{{ $item->url }}">{{ $item->title }}</a>
        @if (!empty($item->children))
            <ul class="submenu">
                @include('partials.menu', ['items' => $item->children])
            </ul>
        @endif
    </li>
@endforeach
