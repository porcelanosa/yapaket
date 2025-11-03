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
use MoonShine\Laravel\TypeCasts\ModelCaster;
use MoonShine\Support\Attributes\Icon;
use MoonShine\Support\Enums\ToastType;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Tabs;
use MoonShine\UI\Components\Tabs\Tab;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;
use MrVaco\Moonshine\Settings\Models\Settings;
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
            ->fillCast($this->settings, new ModelCaster(Settings::class))
            ->fields([
              Tabs::make([
                  // ====== ОБЩИЕ НАСТРОЙКИ ======
                Tab::make('Общие настройки', [
                  Box::make([
                    Text::make('Название сайта', 'general.site_name')
                        ->placeholder('Yapaket')
                        ->hint('Отображается в шапке сайта и в title (siteName)'),
                    Text::make('Описание сайта', 'general.site_description')
                        ->placeholder('Производство пакетов...')
                        ->hint('Краткое описание вашего сайта (siteDescription)'),
                    Text::make('Email администратора', 'general.admin_email')
                        ->placeholder('admin@example.com')
                        ->hint('Email для получения уведомлений (siteEmail)'),
                    Text::make('Телефон', 'general.phone')
                        ->placeholder('+7 (___) ___-__-__')
                        ->hint('Контактный телефон (sitePhone)'),
                    Text::make('"Чистый" телефон', 'contacts.pure_phone')
                        ->placeholder('+7 (___) ___-__-__')
                        ->hint('Телефон без иконок и т.д. Только цифры. (purePhone)'),
                    Textarea::make('Адрес', 'general.address')
                            ->placeholder('г. Москва, ул. ...')
                            ->hint('Физический адрес компании (siteAddress)'),
                    Textarea::make('Режим работы', 'general.working_hours')
                            ->placeholder('Пн-Пт: 9:00-18:00')
                            ->hint('График работы (siteWorkingHours)'),
                  ]),
                ]),
                  // ====== КОНТАКТЫ ======
                Tab::make('Контакты', [
                  Box::make([
                    Text::make('Email для заявок', 'contacts.orders_email')
                        ->placeholder('orders@example.com')
                        ->hint('Email для получения заявок с сайта (ordersEmail)'),
                    Text::make('Email для вопросов', 'contacts.support_email')
                        ->placeholder('support@example.com')
                        ->hint('Email для общих вопросов (supportEmail)'),
                    Text::make('WhatsApp', 'contacts.whatsapp')
                        ->placeholder('+7 (___) ___-__-__')
                        ->hint('Номер для WhatsApp (whatsappPhone)'),
                    Text::make('Telegram', 'contacts.telegram')
                        ->placeholder('@username')
                        ->hint('Telegram username или ссылка (telegramUsername)'),
                  ]),
                ]),
                  // ====== SEO НАСТРОЙКИ ======
                Tab::make('SEO', [
                  Box::make([
                    Text::make('Meta Title по умолчанию', 'seo.default_title')
                        ->extension(new CharCount(max: 58, min: 10))
                        ->placeholder('Yapaket - производство пакетов')
                        ->hint('Заголовок по умолчанию для страниц без своего title (seoDefaultTitle)'),
                    Text::make('Meta Description по умолчанию', 'seo.default_description')
                        ->extension(new CharCount(max: 140, min: 50))
                        ->placeholder('Производство качественных пакетов...')
                        ->hint('Описание по умолчанию для страниц без своего description (seoDefaultDescription)'),
                    Text::make('Meta Keywords', 'seo.keywords')
                        ->placeholder('пакеты, производство, упаковка')
                        ->hint('Ключевые слова через запятую (seoKeywords)'),
                    Text::make('Google Analytics ID', 'seo.google_analytics_id')
                        ->placeholder('G-XXXXXXXXXX')
                        ->hint('Идентификатор Google Analytics (googleAnalyticsId)'),
                    Text::make('Yandex Metrika ID', 'seo.yandex_metrika_id')
                        ->placeholder('12345678')
                        ->hint('Идентификатор Яндекс.Метрики (yandexMetrikaId)'),
                    Switcher::make('Индексация поисковиками', 'seo.robots_index')
                            ->hint('Разрешить индексацию сайта поисковыми системами (robotsIndex)'),
                  ]),
                ]),
                  // ====== СОЦИАЛЬНЫЕ СЕТИ ======
                Tab::make('Социальные сети', [
                  Box::make([
                    Text::make('Facebook', 'social.facebook_url')
                        ->placeholder('https://facebook.com/...')
                        ->hint('Ссылка на страницу Facebook (facebookUrl)'),
                    Text::make('Instagram', 'social.instagram_url')
                        ->placeholder('https://instagram.com/...')
                        ->hint('Ссылка на страницу Instagram (instagramUrl)'),
                    Text::make('VK', 'social.vk_url')
                        ->placeholder('https://vk.com/...')
                        ->hint('Ссылка на страницу ВКонтакте (vkUrl)'),
                    Text::make('YouTube', 'social.youtube_url')
                        ->placeholder('https://youtube.com/...')
                        ->hint('Ссылка на канал YouTube (youtubeUrl)'),
                    Text::make('OK', 'social.ok_url')
                        ->placeholder('https://ok.ru/...')
                        ->hint('Ссылка на страницу Одноклассники (okUrl)'),
                    Text::make('Twitter / X', 'social.twitter_url')
                        ->placeholder('https://twitter.com/...')
                        ->hint('Ссылка на страницу Twitter/X (twitterUrl)'),
                  ]),
                ]),
                  // ====== НАСТРОЙКИ EMAIL ======
                Tab::make('Email рассылка', [
                  Box::make([
                    Text::make('Email отправителя', 'email.from_email')
                        ->placeholder('noreply@example.com')
                        ->hint('Email, от имени которого отправляются письма (emailFromAddress)'),
                    Text::make('Имя отправителя', 'email.from_name')
                        ->placeholder('Yapaket')
                        ->hint('Имя, отображаемое в письмах (emailFromName)'),
                    Text::make('Email для уведомлений', 'email.notification_email')
                        ->placeholder('admin@example.com')
                        ->hint('Email для получения системных уведомлений (notificationEmail)'),
                    Switcher::make('Отправка email уведомлений', 'email.notifications_enabled')
                            ->hint('Включить отправку email уведомлений (emailNotificationsEnabled)'),
                  ]),
                ]),
                  // ====== ДОПОЛНИТЕЛЬНО ======
                Tab::make('Дополнительно', [
                  Box::make([
                    Text::make('Название компании', 'additional.company_name')
                        ->placeholder('ООО "ЯПакет"')
                        ->hint('Юридическое название компании (companyName)'),
                    Text::make('ИНН', 'additional.inn')
                        ->placeholder('1234567890')
                        ->hint('ИНН компании (companyInn)'),
                    Text::make('ОГРН', 'additional.ogrn')
                        ->placeholder('1234567890123')
                        ->hint('ОГРН компании (companyOgrn)'),
                    Textarea::make('Реквизиты', 'additional.requisites')
                            ->placeholder('Банк: ...')
                            ->hint('Полные реквизиты компании (companyRequisites)'),
                    Textarea::make('Политика конфиденциальности (URL)', 'additional.privacy_policy_url')
                            ->placeholder('/privacy-policy')
                            ->hint('Ссылка на страницу политики конфиденциальности (privacyPolicyUrl)'),
                    Switcher::make('Режим обслуживания', 'additional.maintenance_mode')
                            ->hint('Включить режим обслуживания (сайт будет недоступен) (maintenanceMode)'),
                  ]),
                ]),
              ])

            ])
            ->submit('Сохранить настройки',['class' => 'btn btn-primary']);
    }

    /**
     * @return list<ComponentContract>
     * @throws \Throwable
     */
    protected function components(): iterable
    {
        return [
            $this->form()
        ];
    }
}
