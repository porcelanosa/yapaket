<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use MrVaco\Moonshine\Settings\SettingsService;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settingsService = app(SettingsService::class);

        // Проверяем, не заполнены ли уже настройки
        $existing = $settingsService->get('site_settings');
        if ($existing && !empty($existing->value)) {
            $this->command->info('Настройки уже существуют. Пропускаем...');
            return;
        }

        // Начальные настройки
        $defaultSettings = [
            // Общие настройки
            'general' => [
                'site_name' => 'Yapaket',
                'site_description' => 'Производство качественных пакетов',
                'admin_email' => 'admin@yapaket.local',
                'phone' => '+7 (___) ___-__-__',
                'address' => '',
                'working_hours' => 'Пн-Пт: 9:00-18:00',
            ],

            // SEO настройки
            'seo' => [
                'default_title' => 'Yapaket - Производство пакетов',
                'default_description' => 'Производство качественных пакетов для вашего бизнеса',
                'keywords' => 'пакеты, производство, упаковка',
                'google_analytics_id' => '',
                'yandex_metrika_id' => '',
                'robots_index' => true,
            ],

            // Контакты
            'contacts' => [
                'orders_email' => 'orders@yapaket.local',
                'support_email' => 'support@yapaket.local',
                'sales_phone' => '',
                'whatsapp' => '',
                'telegram' => '',
            ],

            // Социальные сети
            'social' => [
                'facebook_url' => '',
                'instagram_url' => '',
                'vk_url' => '',
                'youtube_url' => '',
                'ok_url' => '',
                'twitter_url' => '',
            ],

            // Email настройки
            'email' => [
                'from_email' => 'noreply@yapaket.local',
                'from_name' => 'Yapaket',
                'notification_email' => 'admin@yapaket.local',
                'notifications_enabled' => true,
            ],

            // Дополнительные настройки
            'additional' => [
                'company_name' => 'ООО "Япакет"',
                'inn' => '',
                'ogrn' => '',
                'requisites' => '',
                'privacy_policy_url' => '/privacy-policy',
                'maintenance_mode' => false,
            ],
        ];

        // Сохраняем настройки
        $settingsService->set('site_settings', $defaultSettings);

        $this->command->info('Начальные настройки успешно созданы!');
    }
}
