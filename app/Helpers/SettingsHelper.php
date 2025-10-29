<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use MrVaco\Moonshine\Settings\SettingsService;

class SettingsHelper
{
    protected static string $cacheKey = 'site_settings_cache';
    protected static int $cacheTtl = 3600; // 1 час
    protected static ?array $runtimeCache = null; // Runtime cache для избежания повторных обращений

    /**
     * Получить значение настройки
     * 
     * @param string $key Ключ настройки (например: 'general.site_name')
     * @param mixed $default Значение по умолчанию
     * @return mixed
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $settings = static::getAll();
        
        // Поддержка точечной нотации (general.site_name)
        return data_get($settings, $key, $default);
    }

    /**
     * Получить все настройки (с кешированием)
     * 
     * @return array
     */
    public static function getAll(): array
    {
        // Используем runtime cache для избежания повторных обращений в рамках одного запроса
        if (static::$runtimeCache !== null) {
            return static::$runtimeCache;
        }

        // Используем file cache вместо database для настроек
        static::$runtimeCache = \Illuminate\Support\Facades\Cache::store('file')
            ->remember(static::$cacheKey, static::$cacheTtl, function () {
                $settingsService = app(SettingsService::class);
                $settingsModel = $settingsService->get('site_settings');
                
                return $settingsModel?->value ?? [];
            });

        return static::$runtimeCache;
    }

    /**
     * Установить значение настройки
     * 
     * @param string $key Ключ настройки
     * @param mixed $value Значение
     * @return void
     */
    public static function set(string $key, mixed $value): void
    {
        $settings = static::getAll();
        
        // Устанавливаем значение через точечную нотацию
        data_set($settings, $key, $value);
        
        // Сохраняем все настройки
        $settingsService = app(SettingsService::class);
        $settingsService->set('site_settings', $settings);
        
        // Очищаем кеш
        static::clearCache();
    }

    /**
     * Очистить кеш настроек
     * 
     * @return void
     */
    public static function clearCache(): void
    {
        static::$runtimeCache = null; // Очищаем runtime cache
        \Illuminate\Support\Facades\Cache::store('file')->forget(static::$cacheKey);
    }

    /**
     * Проверить, существует ли настройка
     * 
     * @param string $key
     * @return bool
     */
    public static function has(string $key): bool
    {
        $settings = static::getAll();
        return data_get($settings, $key) !== null;
    }

    /**
     * Получить настройку сайта (алиас для get)
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function site(string $key, mixed $default = null): mixed
    {
        return static::get("general.{$key}", $default);
    }

    /**
     * Получить SEO настройку
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function seo(string $key, mixed $default = null): mixed
    {
        return static::get("seo.{$key}", $default);
    }

    /**
     * Получить контактную информацию
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function contact(string $key, mixed $default = null): mixed
    {
        return static::get("contacts.{$key}", $default);
    }

    /**
     * Получить ссылку на социальную сеть
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function social(string $key, mixed $default = null): mixed
    {
        return static::get("social.{$key}", $default);
    }
}
