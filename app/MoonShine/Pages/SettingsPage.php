<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Helpers\SettingsHelper;
use Exception;
use Leeto\InputExtensionCharCount\InputExtensions\CharCount;
use MoonShine\Contracts\Core\DependencyInjection\CoreContract;
use MoonShine\Laravel\Http\Responses\MoonShineJsonResponse;
use MoonShine\Laravel\Pages\Page;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Support\Attributes\Icon;
use MoonShine\Support\Enums\ToastType;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Tabs;
use MoonShine\UI\Components\Tabs\Tab;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;
use MrVaco\Moonshine\Settings\SettingsService;
use MoonShine\Support\Enums\Color;

#[Icon('heroicons.outline.cog-6-tooth')]
class SettingsPage extends Page
{
    protected string $settingsKey = 'site_settings';
    protected array $settings = [];

    public function __construct(
        CoreContract $core,
        protected SettingsService $settingsService
    ) {
        parent::__construct($core);
    }

    /**
     * @return array<string, string>
     */
    public function getBreadcrumbs(): array
    {
        return [
            '#' => $this->getTitle()
        ];
    }

    public function getTitle(): string
    {
        return $this->title ?: 'Настройки сайта';
    }

    public function getAlias(): ?string
    {
        return 'settings';
    }

    protected function prepareBeforeRender(): void
    {
        parent::prepareBeforeRender();

        $settingsModel = $this->settingsService->get($this->settingsKey);
        $this->settings = $settingsModel?->value ?? [];
    }

    public function store(): MoonShineJsonResponse
    {
        $data = request()->all();
        
        try {
            $this->settingsService->set($this->settingsKey, $data);
            
            // Очищаем кеш настроек после сохранения
            SettingsHelper::clearCache();
            
            return MoonShineJsonResponse::make()
                ->toast('Настройки успешно сохранены', ToastType::SUCCESS);
        } catch (Exception $e) {
            return MoonShineJsonResponse::make()
                ->toast('Ошибка при сохранении настроек: ' . $e->getMessage(), ToastType::ERROR);
        }
    }

    /**
     * @throws \Throwable
     */
    private function form(): FormBuilder
    {
        return FormBuilder::make()
            ->asyncMethod('store')
            ->fillCast($this->settings, new \MoonShine\Laravel\TypeCasts\ModelCaster(\MrVaco\Moonshine\Settings\Models\Settings::class))
            ->fields([
                Tabs::make([
                    // ====== ОБЩИЕ НАСТРОЙКИ ======
                    Tab::make('Общие настройки', [
                        Box::make([
                            Text::make('Название сайта', 'general.site_name')
                                ->placeholder('Yapaket')
                                ->hint('Отображается в шапке сайта и в title'),

                            Text::make('Описание сайта', 'general.site_description')
                                ->placeholder('Производство пакетов...')
                                ->hint('Краткое описание вашего сайта'),

                            Text::make('Email администратора', 'general.admin_email')
                                ->placeholder('admin@example.com')
                                ->hint('Email для получения уведомлений'),

                            Text::make('Телефон', 'general.phone')
                                ->placeholder('+7 (___) ___-__-__')
                                ->hint('Контактный телефон'),

                            Textarea::make('Адрес', 'general.address')
                                ->placeholder('г. Москва, ул. ...')
                                ->hint('Физический адрес компании'),

                            Textarea::make('Режим работы', 'general.working_hours')
                                ->placeholder('Пн-Пт: 9:00-18:00')
                                ->hint('График работы'),
                        ]),
                    ]),

                    // ====== SEO НАСТРОЙКИ ======
                    Tab::make('SEO', [
                        Box::make([
                            Text::make('Meta Title по умолчанию', 'seo.default_title')
                                ->extension(new CharCount(max: 58,min: 10))
                                ->placeholder('Yapaket - производство пакетов')
                                ->hint('Заголовок по умолчанию для страниц без своего title'),

                            Text::make('Meta Description по умолчанию', 'seo.default_description')
                                    ->extension(new CharCount(max: 140, min: 50))
                                ->placeholder('Производство качественных пакетов...')
                                ->hint('Описание по умолчанию для страниц без своего description'),

                            Text::make('Meta Keywords', 'seo.keywords')
                                ->placeholder('пакеты, производство, упаковка')
                                ->hint('Ключевые слова через запятую'),

                            Text::make('Google Analytics ID', 'seo.google_analytics_id')
                                ->placeholder('G-XXXXXXXXXX')
                                ->hint('Идентификатор Google Analytics'),

                            Text::make('Yandex Metrika ID', 'seo.yandex_metrika_id')
                                ->placeholder('12345678')
                                ->hint('Идентификатор Яндекс.Метрики'),

                            Switcher::make('Индексация поисковиками', 'seo.robots_index')
                                ->hint('Разрешить индексацию сайта поисковыми системами'),
                        ]),
                    ]),

                    // ====== КОНТАКТЫ ======
                    Tab::make('Контакты', [
                        Box::make([
                            Text::make('Email для заявок', 'contacts.orders_email')
                                ->placeholder('orders@example.com')
                                ->hint('Email для получения заявок с сайта'),

                            Text::make('Email для вопросов', 'contacts.support_email')
                                ->placeholder('support@example.com')
                                ->hint('Email для общих вопросов'),

                            Text::make('Телефон продаж', 'contacts.sales_phone')
                                ->placeholder('+7 (___) ___-__-__')
                                ->hint('Телефон отдела продаж'),

                            Text::make('WhatsApp', 'contacts.whatsapp')
                                ->placeholder('+7 (___) ___-__-__')
                                ->hint('Номер для WhatsApp'),

                            Text::make('Telegram', 'contacts.telegram')
                                ->placeholder('@username')
                                ->hint('Telegram username или ссылка'),
                        ]),
                    ]),

                    // ====== СОЦИАЛЬНЫЕ СЕТИ ======
                    Tab::make('Социальные сети', [
                        Box::make([
                            Text::make('Facebook', 'social.facebook_url')
                                ->placeholder('https://facebook.com/...')
                                ->hint('Ссылка на страницу Facebook'),

                            Text::make('Instagram', 'social.instagram_url')
                                ->placeholder('https://instagram.com/...')
                                ->hint('Ссылка на страницу Instagram'),

                            Text::make('VK', 'social.vk_url')
                                ->placeholder('https://vk.com/...')
                                ->hint('Ссылка на страницу ВКонтакте'),

                            Text::make('YouTube', 'social.youtube_url')
                                ->placeholder('https://youtube.com/...')
                                ->hint('Ссылка на канал YouTube'),

                            Text::make('OK', 'social.ok_url')
                                ->placeholder('https://ok.ru/...')
                                ->hint('Ссылка на страницу Одноклассники'),

                            Text::make('Twitter / X', 'social.twitter_url')
                                ->placeholder('https://twitter.com/...')
                                ->hint('Ссылка на страницу Twitter/X'),
                        ]),
                    ]),

                    // ====== НАСТРОЙКИ EMAIL ======
                    Tab::make('Email рассылка', [
                        Box::make([
                            Text::make('Email отправителя', 'email.from_email')
                                ->placeholder('noreply@example.com')
                                ->hint('Email, от имени которого отправляются письма'),

                            Text::make('Имя отправителя', 'email.from_name')
                                ->placeholder('Yapaket')
                                ->hint('Имя, отображаемое в письмах'),

                            Text::make('Email для уведомлений', 'email.notification_email')
                                ->placeholder('admin@example.com')
                                ->hint('Email для получения системных уведомлений'),

                            Switcher::make('Отправка email уведомлений', 'email.notifications_enabled')
                                ->hint('Включить отправку email уведомлений'),
                        ]),
                    ]),

                    // ====== ДОПОЛНИТЕЛЬНО ======
                    Tab::make('Дополнительно', [
                        Box::make([
                            Text::make('Название компании', 'additional.company_name')
                                ->placeholder('ООО "Япакет"')
                                ->hint('Юридическое название компании'),

                            Text::make('ИНН', 'additional.inn')
                                ->placeholder('1234567890')
                                ->hint('ИНН компании'),

                            Text::make('ОГРН', 'additional.ogrn')
                                ->placeholder('1234567890123')
                                ->hint('ОГРН компании'),

                            Textarea::make('Реквизиты', 'additional.requisites')
                                ->placeholder('Банк: ...')
                                ->hint('Полные реквизиты компании'),

                            Textarea::make('Политика конфиденциальности (URL)', 'additional.privacy_policy_url')
                                ->placeholder('/privacy-policy')
                                ->hint('Ссылка на страницу политики конфиденциальности'),

                            Switcher::make('Режим обслуживания', 'additional.maintenance_mode')
                                ->hint('Включить режим обслуживания (сайт будет недоступен)'),
                        ]),
                    ]),
                ]),
            ])
            ->submit('Сохранить настройки');
    }

    /**
     * @return list<ComponentContract>
     */
    protected function components(): iterable
    {
        return [
            $this->form()
        ];
    }
}
