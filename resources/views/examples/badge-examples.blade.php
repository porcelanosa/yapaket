<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YP Badge Examples</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-8">YP Badge Component Examples</h1>

        <!-- Базовые цвета -->
        <section class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-semibold mb-4">Базовые цвета (rounded="sm")</h2>
            <div class="flex flex-wrap gap-3">
                <x-yp.yp-badge color="red">Red Badge</x-yp.yp-badge>
                <x-yp.yp-badge color="green">Green Badge</x-yp.yp-badge>
                <x-yp.yp-badge color="blue">Blue Badge</x-yp.yp-badge>
                <x-yp.yp-badge color="yellow">Yellow Badge</x-yp.yp-badge>
                <x-yp.yp-badge color="gray">Gray Badge</x-yp.yp-badge>
                <x-yp.yp-badge color="orange">Orange Badge</x-yp.yp-badge>
                <x-yp.yp-badge color="purple">Purple Badge</x-yp.yp-badge>
                <x-yp.yp-badge color="pink">Pink Badge</x-yp.yp-badge>
            </div>
        </section>

        <!-- Большое скругление -->
        <section class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-semibold mb-4">Большое скругление (rounded="lg")</h2>
            <div class="flex flex-wrap gap-3">
                <x-yp.yp-badge color="red" rounded="lg">Red Badge</x-yp.yp-badge>
                <x-yp.yp-badge color="green" rounded="lg">Green Badge</x-yp.yp-badge>
                <x-yp.yp-badge color="blue" rounded="lg">Blue Badge</x-yp.yp-badge>
                <x-yp.yp-badge color="yellow" rounded="lg">Yellow Badge</x-yp.yp-badge>
                <x-yp.yp-badge color="gray" rounded="lg">Gray Badge</x-yp.yp-badge>
            </div>
        </section>

        <!-- С иконками Umbra UI -->
        <section class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-semibold mb-4">С иконками (Umbra UI)</h2>
            <div class="flex flex-wrap gap-3">
                <x-yp.yp-badge color="red">
                    <x-umbra-ui::icons.circle-alert />
                    Alert
                </x-yp.yp-badge>

                <x-yp.yp-badge color="green">
                    <x-umbra-ui::icons.check />
                    Success
                </x-yp.yp-badge>

                <x-yp.yp-badge color="blue">
                    <x-umbra-ui::icons.info />
                    Info
                </x-yp.yp-badge>

                <x-yp.yp-badge color="yellow">
                    <x-umbra-ui::icons.triangle-alert />
                    Warning
                </x-yp.yp-badge>

                <x-yp.yp-badge color="purple">
                    <x-umbra-ui::icons.star />
                    Featured
                </x-yp.yp-badge>
            </div>
        </section>

        <!-- С иконками и большим скруглением -->
        <section class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-semibold mb-4">С иконками + rounded="lg"</h2>
            <div class="flex flex-wrap gap-3">
                <x-yp.yp-badge color="red" rounded="lg">
                    <x-umbra-ui::icons.circle-x />
                    Error
                </x-yp.yp-badge>

                <x-yp.yp-badge color="green" rounded="lg">
                    <x-umbra-ui::icons.circle-check />
                    Completed
                </x-yp.yp-badge>

                <x-yp.yp-badge color="orange" rounded="lg">
                    <x-umbra-ui::icons.clock />
                    Pending
                </x-yp.yp-badge>
            </div>
        </section>

        <!-- Практические примеры -->
        <section class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-semibold mb-4">Практические примеры использования</h2>
            
            <div class="space-y-4">
                <!-- Статус заказа -->
                <div class="flex items-center gap-3">
                    <span class="text-gray-700">Заказ #12345:</span>
                    <x-yp.yp-badge color="green">
                        <x-umbra-ui::icons.check />
                        Доставлен
                    </x-yp.yp-badge>
                </div>

                <!-- Уведомление -->
                <div class="flex items-center gap-3">
                    <span class="text-gray-700">Уведомления:</span>
                    <x-yp.yp-badge color="red" rounded="lg">5 новых</x-yp.yp-badge>
                </div>

                <!-- Теги -->
                <div class="flex items-center gap-3">
                    <span class="text-gray-700">Теги:</span>
                    <x-yp.yp-badge color="blue" rounded="lg">Laravel</x-yp.yp-badge>
                    <x-yp.yp-badge color="purple" rounded="lg">Tailwind</x-yp.yp-badge>
                    <x-yp.yp-badge color="pink" rounded="lg">PHP</x-yp.yp-badge>
                </div>

                <!-- Статус пользователя -->
                <div class="flex items-center gap-3">
                    <span class="text-gray-700">Статус:</span>
                    <x-yp.yp-badge color="green">
                        <x-umbra-ui::icons.circle />
                        Online
                    </x-yp.yp-badge>
                </div>
            </div>
        </section>

        <!-- С кастомными классами -->
        <section class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-semibold mb-4">С дополнительными классами</h2>
            <div class="flex flex-wrap gap-3">
                <x-yp.yp-badge color="blue" class="text-sm px-4 py-1">Большой badge</x-yp.yp-badge>
                <x-yp.yp-badge color="red" class="uppercase tracking-wide">Important</x-yp.yp-badge>
                <x-yp.yp-badge color="green" class="shadow-lg">С тенью</x-yp.yp-badge>
            </div>
        </section>

    </div>
</body>
</html>
