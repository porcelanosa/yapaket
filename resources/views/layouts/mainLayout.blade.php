{{-- resources/views/layouts/mainLayout.blade.php --}}
        <!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'YaPaket')</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 text-gray-900 antialiased">

<x-header />

<div class="container mx-auto flex py-6 px-4">

    <x-left-sidebar class="hidden lg:block w-64 mr-6" />

    <main class="flex-1">
        @yield('content')
    </main>

    <x-right-sidebar class="hidden xl:block w-64 ml-6" />

</div>
<x-footer />

@vite('resources/js/app.js')
</body>
</html>
