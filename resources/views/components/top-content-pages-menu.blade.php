<div class="container mx-auto px-4 py-0 xl:hidden">

    <h3 class="font-bold text-red-600 pb-1 mb-3">
        Интересные предложения
    </h3>
    <div class="flex flex-wrap gap-3">
    @foreach($homePages as $page)
            <x-yp.yp-badge color="light-gray">
                <a href="{{ route('pages.show', $page->slug) }}" class="text-gray-700 hover:text-red-600 badge">
                    {{ $page->name }}
                </a>
            </x-yp.yp-badge>
    @endforeach
        </div>
</div>