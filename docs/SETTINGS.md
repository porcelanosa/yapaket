# 📝 Система настроек сайта

## 🚀 Установка

Миграция уже применена, таблица `settings` создана.

Для заполнения начальными настройками выполните:

```bash
php artisan db:seed --class=SettingsSeeder
```

После внесения изменений в helper-функции, выполните:

```bash
composer dump-autoload
```

## 📋 Доступ к настройкам

### Через helper-функции (рекомендуется):

```php
// Получить любую настройку
setting('general.site_name'); // 'Yapaket'
setting('seo.default_title'); // 'Yapaket - Производство пакетов'

// С дефолтным значением
setting('general.site_name', 'Default Name');

// Получить все настройки
$allSettings = setting();

// Сокращенные алиасы
site_setting('site_name'); // Аналог setting('general.site_name')
seo_setting('default_title'); // Аналог setting('seo.default_title')
contact_setting('orders_email'); // Аналог setting('contacts.orders_email')
social_setting('facebook_url'); // Аналог setting('social.facebook_url')
```

### Через SettingsHelper:

```php
use App\Helpers\SettingsHelper;

// Получить настройку
SettingsHelper::get('general.site_name');

// Установить настройку
SettingsHelper::set('general.site_name', 'New Name');

// Проверить существование
SettingsHelper::has('general.site_name'); // true

// Очистить кеш
SettingsHelper::clearCache();

// Получить все настройки
$settings = SettingsHelper::getAll();
```

## 🎨 Использование в Blade-шаблонах

```blade
{{-- В header --}}
<title>{{ setting('seo.default_title') }}</title>
<meta name="description" content="{{ setting('seo.default_description') }}">

{{-- В footer --}}
<p>{{ site_setting('site_name') }}</p>
<p>{{ site_setting('address') }}</p>
<p>{{ site_setting('phone') }}</p>

{{-- Социальные сети --}}
@if(social_setting('facebook_url'))
    <a href="{{ social_setting('facebook_url') }}">Facebook</a>
@endif
```

## 📂 Структура настроек

### general (Общие настройки)
- `site_name` - Название сайта
- `site_description` - Описание сайта
- `admin_email` - Email администратора
- `phone` - Контактный телефон
- `address` - Адрес компании
- `working_hours` - Режим работы

### seo (SEO настройки)
- `default_title` - Meta title по умолчанию
- `default_description` - Meta description по умолчанию
- `keywords` - Ключевые слова
- `google_analytics_id` - Google Analytics ID
- `yandex_metrika_id` - Yandex Metrika ID
- `robots_index` - Индексация поисковиками

### contacts (Контакты)
- `orders_email` - Email для заявок
- `support_email` - Email для вопросов
- `sales_phone` - Телефон продаж
- `whatsapp` - WhatsApp номер
- `telegram` - Telegram

### social (Социальные сети)
- `facebook_url` - Facebook URL
- `instagram_url` - Instagram URL
- `vk_url` - VK URL
- `youtube_url` - YouTube URL
- `ok_url` - OK URL
- `twitter_url` - Twitter/X URL

### email (Email настройки)
- `from_email` - Email отправителя
- `from_name` - Имя отправителя
- `notification_email` - Email для уведомлений
- `notifications_enabled` - Включить отправку уведомлений

### additional (Дополнительно)
- `company_name` - Название компании
- `inn` - ИНН
- `ogrn` - ОГРН
- `requisites` - Реквизиты
- `privacy_policy_url` - URL политики конфиденциальности
- `maintenance_mode` - Режим обслуживания

## 🔧 Управление через админ-панель

Зайдите в админ-панель MoonShine → "Настройки сайта"

Все настройки разделены на вкладки:
- Общие настройки
- SEO
- Контакты
- Социальные сети
- Email рассылка
- Дополнительно

## 💡 Примеры использования

### В контроллере:

```php
public function index()
{
    $siteName = setting('general.site_name');
    $phone = site_setting('phone');
    
    return view('welcome', compact('siteName', 'phone'));
}
```

### В middleware (режим обслуживания):

```php
public function handle($request, Closure $next)
{
    if (setting('additional.maintenance_mode') && !auth()->check()) {
        return response()->view('maintenance');
    }
    
    return $next($request);
}
```

### В мейле:

```php
Mail::send(...)->from(
    setting('email.from_email'),
    setting('email.from_name')
);
```

## 🗑️ Очистка кеша

Настройки кешируются на 1 час. Для очистки кеша:

```php
SettingsHelper::clearCache();
```

Или через Artisan:

```bash
php artisan cache:forget site_settings_cache
```

## 🔐 Безопасность

- Настройки хранятся в базе данных
- Доступ к изменению только через админ-панель MoonShine
- Кеширование для оптимизации производительности
- Валидация данных при сохранении

## 📝 Добавление новых настроек

1. Добавьте поле в `SettingsPage.php` в нужную вкладку
2. Обновите `SettingsSeeder.php` с дефолтным значением
3. Используйте через `setting('category.key')`

Пример добавления новой настройки:

```php
// В SettingsPage.php
Text::make('Новая настройка', 'general.new_setting')

// В SettingsSeeder.php
'general' => [
    'new_setting' => 'default value',
    // ...
]

// Использование
setting('general.new_setting')
```
