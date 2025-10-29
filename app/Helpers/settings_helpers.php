<?php

use App\Helpers\SettingsHelper;

if (!function_exists('setting')) {
    /**
     * Получить значение настройки
     * 
     * @param string|null $key Ключ настройки (например: 'general.site_name')
     * @param mixed $default Значение по умолчанию
     * @return mixed
     * 
     * @example
     * setting('general.site_name') // Вернет название сайта
     * setting('general.site_name', 'Default Name') // С дефолтным значением
     * setting() // Вернет весь массив настроек
     */
    function setting(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return SettingsHelper::getAll();
        }
        
        return SettingsHelper::get($key, $default);
    }
}

if (!function_exists('site_setting')) {
    /**
     * Получить общую настройку сайта (из раздела general)
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     * 
     * @example
     * site_setting('site_name') // Аналогично setting('general.site_name')
     */
    function site_setting(string $key, mixed $default = null): mixed
    {
        return SettingsHelper::site($key, $default);
    }
}

if (!function_exists('seo_setting')) {
    /**
     * Получить SEO настройку
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     * 
     * @example
     * seo_setting('default_title') // Аналогично setting('seo.default_title')
     */
    function seo_setting(string $key, mixed $default = null): mixed
    {
        return SettingsHelper::seo($key, $default);
    }
}

if (!function_exists('contact_setting')) {
    /**
     * Получить контактную информацию
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     * 
     * @example
     * contact_setting('orders_email') // Аналогично setting('contacts.orders_email')
     */
    function contact_setting(string $key, mixed $default = null): mixed
    {
        return SettingsHelper::contact($key, $default);
    }
}

if (!function_exists('social_setting')) {
    /**
     * Получить ссылку на социальную сеть
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     * 
     * @example
     * social_setting('facebook_url') // Аналогично setting('social.facebook_url')
     */
    function social_setting(string $key, mixed $default = null): mixed
    {
        return SettingsHelper::social($key, $default);
    }
}
