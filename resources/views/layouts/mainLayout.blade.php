{{-- resources/views/layouts/mainLayout.blade.php --}}

<!DOCTYPE html>
<html lang="ru" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <link rel="manifest" href="/site.webmanifest" />
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
