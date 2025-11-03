<section class="contact-form">
    <h2>Свяжитесь с нами</h2>
    <form method="POST" action="/{{--{{ route('contacts.send') }}--}}">
        @csrf
        <x-yp.yp-input name="name" label="Ваше имя"/>
        <x-yp.yp-input name="email" label="E-mail"/>
{{--        <x-textarea name="message" label="Сообщение"/>--}}
        <x-yp.yp-button type="submit">Отправить</x-yp.yp-button>
    </form>
</section>
