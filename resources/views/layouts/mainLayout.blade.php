{{-- resources/views/layouts/mainLayout.blade.php --}}

<!DOCTYPE html>
<html lang="ru" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $seoDefaultTitle) | YaPaket</title>
    <meta content="@yield('meta_description', $seoDefaultDescription)" name="description">
    @vite('resources/css/app.css')
</head>
<body>

<x-header />

<div class="container mx-auto flex py-6 px-4">

    <x-left-sidebar class="hidden lg:block w-64 mr-6" />

    <main class="flex-1">
        <x-breadcrumbs :breadcrumbs="$breadcrumbs ?? collect()" class="mb-6" />

        @yield('content')
    </main>

    <x-right-sidebar class="hidden xl:block w-64 ml-6" />

</div>
<x-footer />

@vite('resources/js/app.js')
@stack('scripts')
</body>
</html>
