<?php

namespace App\View\Composers;

use App\Helpers\SettingsHelper;
use Illuminate\View\View;

class SettingsComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        // Проверяем, что это не админка (двойная проверка на случай, если в провайдере не сработало)
        // Убрал проверяестя во ViewServiceProvider
//        if (request()->is('admin/*') || request()->is('moonshine/*')) {
//            return;
//        }

        // Загружаем все настройки один раз
        $settings = SettingsHelper::getAll();
        $view->with('siteSettings', $settings);
        
        // ====== ОБЩИЕ НАСТРОЙКИ ======
        $view->with('siteName', $settings['general']['site_name'] ?? 'Yapaket');
        $view->with('siteDescription', $settings['general']['site_description'] ?? '');
        $view->with('siteEmail', $settings['general']['admin_email'] ?? '');
        $view->with('sitePhone', $settings['general']['phone'] ?? '');
        $view->with('siteAddress', $settings['general']['address'] ?? '');
        $view->with('siteWorkingHours', $settings['general']['working_hours'] ?? '');
        // ====== КОНТАКТЫ ======
        $view->with('ordersEmail', $settings['contacts']['orders_email'] ?? '');
        $view->with('supportEmail', $settings['contacts']['support_email'] ?? '');
        $view->with('purePhone', $settings['contacts']['pure_phone'] ?? '');
        $view->with('whatsappPhone', $settings['contacts']['whatsapp'] ?? '');
        $view->with('telegramUsername', $settings['contacts']['telegram'] ?? '');
        
        // ====== SEO НАСТРОЙКИ ======
        $view->with('seoDefaultTitle', $settings['seo']['default_title'] ?? '');
        $view->with('seoDefaultDescription', $settings['seo']['default_description'] ?? '');
        $view->with('seoKeywords', $settings['seo']['keywords'] ?? '');
        $view->with('googleAnalyticsId', $settings['seo']['google_analytics_id'] ?? '');
        $view->with('yandexMetrikaId', $settings['seo']['yandex_metrika_id'] ?? '');
        $view->with('robotsIndex', $settings['seo']['robots_index'] ?? true);

        
        // ====== СОЦИАЛЬНЫЕ СЕТИ ======
        $view->with('facebookUrl', $settings['social']['facebook_url'] ?? '');
        $view->with('instagramUrl', $settings['social']['instagram_url'] ?? '');
        $view->with('vkUrl', $settings['social']['vk_url'] ?? '');
        $view->with('youtubeUrl', $settings['social']['youtube_url'] ?? '');
        $view->with('okUrl', $settings['social']['ok_url'] ?? '');
        $view->with('twitterUrl', $settings['social']['twitter_url'] ?? '');
        
        // ====== EMAIL НАСТРОЙКИ ======
        $view->with('emailFromAddress', $settings['email']['from_email'] ?? '');
        $view->with('emailFromName', $settings['email']['from_name'] ?? '');
        $view->with('notificationEmail', $settings['email']['notification_email'] ?? '');
        $view->with('emailNotificationsEnabled', $settings['email']['notifications_enabled'] ?? true);
        
        // ====== ДОПОЛНИТЕЛЬНЫЕ НАСТРОЙКИ ======
        $view->with('companyName', $settings['additional']['company_name'] ?? '');
        $view->with('companyInn', $settings['additional']['inn'] ?? '');
        $view->with('companyOgrn', $settings['additional']['ogrn'] ?? '');
        $view->with('companyRequisites', $settings['additional']['requisites'] ?? '');
        $view->with('privacyPolicyUrl', $settings['additional']['privacy_policy_url'] ?? '');
        $view->with('maintenanceMode', $settings['additional']['maintenance_mode'] ?? false);
    }
}
