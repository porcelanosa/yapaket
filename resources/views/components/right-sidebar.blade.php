{{-- resources/views/components/right-sidebar.blade.php --}}
@props(['interestingOffers'=>[], 'socials'=>[], 'contact'=>[]])
<aside {{ $attributes->merge(['class' => 'bg-white shadow-md rounded-lg p-4 space-y-6']) }}>

    {{-- Интересные предложения --}}
    <div>
        <h3 class="font-bold text-red-600 border-b border-red-600 pb-1 mb-3">
            Интересные предложения
        </h3>
        <ul class="text-sm space-y-2">
            @foreach($interestingOffers as $offer)
                <li>
                    <a href="{{ $offer['url'] }}" class="text-gray-700 hover:text-red-600">
                        {{ $offer['title'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    {{-- Соцсети --}}
    <div>
        <h3 class="font-bold text-red-600 border-b border-red-600 pb-1 mb-3">
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

    {{-- Контакты --}}
    <div>
        <h3 class="font-bold text-red-600 border-b border-red-600 pb-1 mb-3">
            Контакты
        </h3>
        <ul class="text-sm space-y-2">
            <li>{{ $siteName }}</li>
            <li>{{ $sitePhone }}</li>
            <li>{{ $siteEmail }}</li>
            <li>{{ $siteAddress }}</li>
            <li>📍 {!! $contact['address'] ?? 'Адрес не указан' !!}</li>

            <li>📞 {!! $contact['phone'] ?? 'Телефон не указан'  !!} </li>
            <li>✉️ {!! $contact['email'] ?? 'Почта не указана' !!}</li>
        </ul>
    </div>
</aside>
