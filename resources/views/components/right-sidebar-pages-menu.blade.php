
<h3 class="font-bold text-red-600 pb-1 mb-3 mt-1">
    Интересные предложения
</h3>
<ul class="text-sm space-y-1">
    @foreach($homePages as $page)
        <li>
            <a href="{{ route('pages.show', $page->slug) }}" class="text-gray-700 hover:text-red-600">
                {{ $page->name }}
            </a>
        </li>
    @endforeach
</ul>