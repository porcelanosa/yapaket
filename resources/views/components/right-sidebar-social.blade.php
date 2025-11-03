<div>
    <h3 class="font-bold text-red-600 pb-1 mb-3">
        Мы в соцсетях
    </h3>
    <div class="flex space-x-2">
        @foreach($socials as $social)
            <a href="{{ $social['url'] }}"
               class="w-8 h-8 {{ $social['color'] }} rounded"
               title="{{ $social['name'] }}"></a>
        @endforeach
    </div>
</div>