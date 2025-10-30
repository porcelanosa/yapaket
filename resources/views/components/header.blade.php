{{-- resources/views/components/header.blade.php --}}
@props(['mainNav'=>[]])

<subheader class="bg-white border-0">
    <div class="container mx-auto flex justify-between items-center py-4 px-4">
        <a href="{{ url('/') }}" class="text-2xl font-bold text-red-600">
            Производство брендированных пакетов.
        </a>

        <nav class="hidden md:flex space-x-6">
            @foreach($mainNav as $nav)
                <a href="{{ $nav['url'] }}"
                   class="text-gray-700 hover:text-red-600 font-medium">
                    {{ $nav['title'] }}
                </a>
            @endforeach
        </nav>
    </div>
</subheader>
<!-- Header -->
<header class="bg-white shadow-sm sticky top-0 z-30">
    <div class="container mx-auto px-4">
        <!-- Top row with logo, search, and contact -->
        <div class="flex items-center justify-between py-3">
            <div class="flex items-start flex-col">
                <a href="/" title="на главную страницу пакетов с логотипом" class="no-underline">
                    <div class="text-2xl font-bold">
                        <span class="text-black">Ya</span><span class="bg-red-600 text-white px-1 rounded">Paket</span>
                    </div>
                    <div class="hidden md:block ml-0 text-xs text-gray-500 uppercase tracking-wide">
                        Пакеты с логотипом
                    </div>
                </a>
            </div>
            <!-- Search bar (desktop) -->
            <div class="hidden md:flex flex-1 max-w-md mx-8">
                <div class="relative w-full">
                    <input type="text" placeholder="Искать на сайте"
                           class="w-full border-2 border-dashed border-gray-400 px-3 py-2 text-sm">
                </div>
            </div>
            <!-- Contact info and mobile menu -->
            <div class="flex items-center justify-between space-x-4">
                <div class="hidden md:block text-right">
                    <div class="text-lg font-bold">+7 926 842 66 36</div>
                    <div class="text-sm text-gray-600 underline">24@youprint.ru</div>
                </div>
                <x-yp.yp-button color="red">Задать вопрос&nbsp;
                    <x-fas-question-circle class="w-6 h-6 " />
                </x-yp.yp-button>
                <!-- Mobile menu button -->
                <button id="menu-toggle" class="md:hidden p-2 focus:outline-none">
                    <div class="hamburger-line"></div>
                    <div class="hamburger-line"></div>
                    <div class="hamburger-line"></div>
                </button>
            </div>
        </div>
        <!-- Mobile search -->
        <div class="md:hidden pb-3">
            <input type="text" placeholder="Искать на сайте"
                   class="w-full border-2 border-dashed border-gray-400 px-3 py-2 text-sm">
        </div>
    </div>
</header>
<!-- Mobile menu overlay -->
<div id="menu-overlay" class="menu-overlay"></div>
<!-- Mobile menu -->
<div id="mobile-menu" class="mobile-menu">
    <div class="p-4">
        <!-- Close button -->
        <div class="flex justify-end mb-4">
            <button id="menu-close" class="text-red-600 text-2xl font-bold">✕</button>
        </div>
        <!-- Mobile contact info -->
        <div class="mb-6">
            <div class="text-lg font-bold mb-1">+7 926 842 66 36</div>
            <div class="text-sm text-blue-600 underline mb-4">24@youprint.ru</div>
            <div class="mb-4">
                <div class="font-semibold underline mb-2">Контакты</div>
                <div class="font-semibold underline mb-2">Оплата</div>
                <div class="font-semibold underline">Доставка</div>
            </div>
            <!-- Social icons -->
            <div class="flex space-x-2">
                <div class="w-8 h-8 bg-pink-500 rounded"></div>
                <div class="w-8 h-8 bg-blue-600 rounded"></div>
                <div class="w-8 h-8 bg-blue-800 rounded"></div>
            </div>
        </div>
        <!-- Mobile menu categories -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <h3 class="font-bold text-red-600 border-b border-red-600 pb-1 mb-3">Печать</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="text-gray-700 underline">Календари</a></li>
                    <li><a href="#" class="text-gray-700 underline">Брошюры</a></li>
                    <li><a href="#" class="text-gray-700 underline">Папки</a></li>
                    <li><a href="#" class="text-gray-700 underline">Визитки</a></li>
                    <li><a href="#" class="text-gray-700 underline">Пакеты</a></li>
                    <li><a href="#" class="text-gray-700 underline">Блокноты</a></li>
                    <li><a href="#" class="text-gray-700 underline">Упаковка</a></li>
                    <li><a href="#" class="text-gray-700 underline">Буклеты</a></li>
                    <li><a href="#" class="text-gray-700 underline">Наклейки</a></li>
                    <li><a href="#" class="text-gray-700 underline">Листовки</a></li>
                    <li><a href="#" class="text-gray-700 underline">Конверты</a></li>
                    <li><a href="#" class="text-gray-700 underline">Кубарики</a></li>
                    <li><a href="#" class="text-gray-700 underline">Пригласительные</a></li>
                </ul>
            </div>
            <div>
                <h3 class="font-bold text-red-600 border-b border-red-600 pb-1 mb-3">Сувениры</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="text-gray-700 underline">Ленточки</a></li>
                    <li><a href="#" class="text-gray-700 underline">Спички</a></li>
                    <li><a href="#" class="text-gray-700 underline">Воздушные шары</a></li>
                    <li><a href="#" class="text-gray-700 underline">Ручки</a></li>
                    <li><a href="#" class="text-gray-700 underline">Мешочки подарочные</a></li>
                    <li><a href="#" class="text-gray-700 underline">Новогодние подарки</a></li>
                </ul>
            </div>
        </div>
        <div class="mt-6">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <h3 class="font-bold text-red-600 border-b border-red-600 pb-1 mb-3">Производство</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="text-gray-700 underline">Баннеры</a></li>
                        <li><a href="#" class="text-gray-700 underline">Флаги</a></li>
                        <li><a href="#" class="text-gray-700 underline">Вывески</a></li>
                        <li><a href="#" class="text-gray-700 underline">Таблички</a></li>
                        <li><a href="#" class="text-gray-700 underline">Стенды</a></li>
                        <li><a href="#" class="text-gray-700 underline">Пластиковые карты</a></li>
                        <li><a href="#" class="text-gray-700 underline">Диспенсеры</a></li>
                        <li><a href="#" class="text-gray-700 underline">Хенгеры</a></li>
                        <li><a href="#" class="text-gray-700 underline">Воблеры</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
