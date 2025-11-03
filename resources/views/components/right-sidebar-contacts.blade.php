<div>
    <h3 class="font-bold text-red-600 pb-1 mb-3">
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