<?php

declare(strict_types = 1);

namespace App\MoonShine\Fields;

use MoonShine\AssetManager\Css;
use MoonShine\AssetManager\Js;
use MoonShine\UI\Fields\Textarea;

final class CKEditor extends Textarea
{
    protected string $view   = 'porcelanosa-ckeditor::ckeditor';
    protected array  $config = [];

    protected function assets(): array
    {
        return [
//          Js::make('https://cdn.ckeditor.com/ckeditor5/43.3.1/ckeditor5.umd.js'),
//          Css::make('https://cdn.ckeditor.com/ckeditor5/43.3.1/ckeditor5.css'),
          Css::make('vendor/porcelanosa-ckeditor/ckeditor5-43.css'),
          Js::make('vendor/porcelanosa-ckeditor/ckeditor5-43.umd.js'),
          Js::make('vendor/porcelanosa-ckeditor/ckeditor-lfm.js'),
          Js::make('vendor/porcelanosa-ckeditor/ckeditor-init.js'),
        ];
    }

    /**
     * Получить дефолтную конфигурацию CKEditor 5
     */
    protected function getDefaultConfig(): array
    {
        return [
          'language'    => 'ru',
          'placeholder' => 'Введите текст...',

          'toolbar' => [
            'items' => [
              'undo',
              'redo',
              '|',
              'heading',
              '|',
              'fontSize',
              'fontFamily',
              'fontColor',
              'fontBackgroundColor',
              '|',
              'bold',
              'italic',
              'underline',
              'strikethrough',
              'code',
              '|',
              'link', /*'uploadImage', */ 'insertImageLFM',
              'blockQuote',
              'codeBlock',
              'mediaEmbed',
              '|',
              'alignment',
              '|',
              'bulletedList',
              'numberedList',
              'todoList',
              'outdent',
              'indent',
              '|',
              'insertTable',
              'horizontalLine',
              '|',
              'sourceEditing',
            ],
          ],

          'heading' => [
            'options' => [
              ['model' => 'paragraph', 'title' => 'Параграф', 'class' => 'ck-heading_paragraph'],
                /* ['model' => 'heading1', 'view' => 'h1', 'title' => 'Заголовок 1', 'class' => 'ck-heading_heading1'],*/
              ['model' => 'heading2', 'view' => 'h2', 'title' => 'Заголовок 2', 'class' => 'ck-heading_heading2'],
              ['model' => 'heading3', 'view' => 'h3', 'title' => 'Заголовок 3', 'class' => 'ck-heading_heading3'],
              ['model' => 'heading4', 'view' => 'h4', 'title' => 'Заголовок 4', 'class' => 'ck-heading_heading4'],
              ['model' => 'heading5', 'view' => 'h5', 'title' => 'Заголовок 5', 'class' => 'ck-heading_heading5'],
              ['model' => 'heading6', 'view' => 'h6', 'title' => 'Заголовок 6', 'class' => 'ck-heading_heading6'],
            ],
          ],

          'image' => [

            'resizeOptions' => [
              [
                'name'  => 'resizeImage:original',
                'label' => 'Default image width',
                'value' => null,
              ],
              [
                'name'  => 'resizeImage:50',
                'label' => '50% page width',
                'value' => '50',
              ],
              [
                'name'  => 'resizeImage:75',
                'label' => '75% page width',
                'value' => '75',
              ],
            ],
            'toolbar'       => [
              'imageTextAlternative',
              '|',
              'imageStyle:inline',
              'imageStyle:block',
              'imageStyle:side',
              'imageStyle:wrapText',
              'imageStyle:breakText',
              '|',
              'toggleImageCaption',
              '|',
              'resizeImage',
            ],
          ],

          'table' => [
            'contentToolbar' => ['tableColumn', 'tableRow', 'mergeTableCells'],
          ],
        ];
    }

    // ==================== ОСНОВНЫЕ МЕТОДЫ ====================

    /**
     * Устанавливает endpoint для загрузки файлов (Simple Upload)
     */
    public function attachmentEndpoint(string $url): self
    {
        $this->config['simpleUpload'] = ['uploadUrl' => $url];

        return $this;
    }

    /**
     * Устанавливает начальное содержимое редактора
     */
    public function initialData(string $html): self
    {
        $this->config['initialData'] = $html;

        return $this;
    }

    /**
     * Устанавливает placeholder
     */
    public function placeholderText(string $value): self
    {
        $this->config['placeholder'] = $value;

        return $this;
    }

    /**
     * Устанавливает язык интерфейса
     */
    public function language(string $lang): self
    {
        $this->config['language'] = $lang;

        return $this;
    }

    /**
     * Устанавливает лицензионный ключ
     */
    public function licenseKey(string $key): self
    {
        $this->config['licenseKey'] = $key;

        return $this;
    }

    // ==================== TOOLBAR ====================

    /**
     * Устанавливает конфигурацию toolbar
     */
    public function toolbar(array $config): self
    {
        $this->config['toolbar'] = $config;

        return $this;
    }

    /**
     * Устанавливает только элементы toolbar
     */
    public function toolbarItems(array $items): self
    {
        $this->config['toolbar']['items'] = $items;

        return $this;
    }

    // ==================== PLUGINS ====================

    /**
     * Устанавливает список активных плагинов
     */
    public function plugins(array $plugins): self
    {
        $this->config['plugins'] = $plugins;

        return $this;
    }

    /**
     * Добавляет плагин к существующему списку
     */
    public function addPlugin(string $plugin): self
    {
        if (!isset($this->config['plugins'])) {
            $this->config['plugins'] = $this->getDefaultConfig()['plugins'];
        }
        $this->config['plugins'][] = $plugin;

        return $this;
    }

    /**
     * Удаляет плагины из конфигурации
     */
    public function removePlugins(array $plugins): self
    {
        $this->config['removePlugins'] = $plugins;

        return $this;
    }

    // ==================== ФОРМАТИРОВАНИЕ ====================

    /**
     * Устанавливает конфигурацию заголовков
     */
    public function heading(array $config): self
    {
        $this->config['heading'] = $config;

        return $this;
    }

    /**
     * Устанавливает конфигурацию стилей
     */
    public function styleConfig(array $config): self
    {
        $this->config['style'] = $config;

        return $this;
    }

    /**
     * Устанавливает конфигурацию шрифтов
     */
    public function fontFamily(array $config): self
    {
        $this->config['fontFamily'] = $config;

        return $this;
    }

    /**
     * Устанавливает конфигурацию размеров шрифта
     */
    public function fontSize(array $config): self
    {
        $this->config['fontSize'] = $config;

        return $this;
    }

    // ==================== СПИСКИ ====================

    /**
     * Устанавливает конфигурацию списков
     */
    public function list(array $config): self
    {
        $this->config['list'] = $config;

        return $this;
    }

    // ==================== ИЗОБРАЖЕНИЯ ====================

    /**
     * Устанавливает конфигурацию изображений
     */
    public function image(array $config): self
    {
        $this->config['image'] = $config;

        return $this;
    }

    /**
     * Устанавливает toolbar для изображений
     */
    public function imageToolbar(array $items): self
    {
        $this->config['image']['toolbar'] = $items;

        return $this;
    }

    // ==================== ТАБЛИЦЫ ====================

    /**
     * Устанавливает конфигурацию таблиц
     */
    public function table(array $config): self
    {
        $this->config['table'] = $config;

        return $this;
    }

    /**
     * Устанавливает toolbar для таблиц
     */
    public function tableToolbar(array $items): self
    {
        $this->config['table']['contentToolbar'] = $items;

        return $this;
    }

    // ==================== ССЫЛКИ ====================

    /**
     * Устанавливает конфигурацию ссылок
     */
    public function linkConfig(array $config): self
    {
        $this->config['link'] = $config;

        return $this;
    }

    // ==================== HTML & CODE ====================

    /**
     * Устанавливает конфигурацию HTML поддержки
     */
    public function htmlSupport(array $config): self
    {
        $this->config['htmlSupport'] = $config;

        return $this;
    }

    /**
     * Устанавливает конфигурацию блоков кода
     */
    public function codeBlock(array $config): self
    {
        $this->config['codeBlock'] = $config;

        return $this;
    }

    /**
     * Устанавливает конфигурацию HTML embed
     */
    public function htmlEmbed(array $config): self
    {
        $this->config['htmlEmbed'] = $config;

        return $this;
    }

    // ==================== ДОПОЛНИТЕЛЬНЫЕ ФУНКЦИИ ====================

    /**
     * Устанавливает конфигурацию подсветки текста
     */
    public function highlight(array $config): self
    {
        $this->config['highlight'] = $config;

        return $this;
    }

    /**
     * Устанавливает конфигурацию упоминаний
     */
    public function mention(array $config): self
    {
        $this->config['mention'] = $config;

        return $this;
    }

    /**
     * Устанавливает конфигурацию медиа-контента
     */
    public function mediaEmbed(array $config): self
    {
        $this->config['mediaEmbed'] = $config;

        return $this;
    }

    // ==================== LARAVEL FILEMANAGER ====================

    /**
     * Быстрая настройка Laravel File Manager
     * Включает браузер и загрузку изображений через LFM
     */
    public function withFileManager(string $prefix = '/laravel-filemanager'): self
    {
        $this->config['lfmConfig'] = [
          'prefix' => $prefix,
          'type'   => 'image', // 'image' или 'file'
        ];

        return $this;
    }

    /**
     * Устанавливает тип файлов для Laravel FileManager
     */
    public function fileManagerType(string $type): self
    {
        if (!isset($this->config['lfmConfig'])) {
            $this->config['lfmConfig'] = ['prefix' => '/laravel-filemanager'];
        }
        $this->config['lfmConfig']['type'] = $type;

        return $this;
    }

    /**
     * Устанавливает URL для браузера изображений (Laravel FileManager)
     */
    public function filebrowserImageBrowseUrl(string $url): self
    {
        $this->config['filebrowserImageBrowseUrl'] = $url;

        return $this;
    }

    /**
     * Устанавливает URL для загрузки изображений (Laravel FileManager)
     */
    public function filebrowserImageUploadUrl(string $url): self
    {
        $this->config['filebrowserImageUploadUrl'] = $url;

        return $this;
    }

    /**
     * Устанавливает URL для браузера файлов (Laravel FileManager)
     */
    public function filebrowserBrowseUrl(string $url): self
    {
        $this->config['filebrowserBrowseUrl'] = $url;

        return $this;
    }

    /**
     * Устанавливает URL для загрузки файлов (Laravel FileManager)
     */
    public function filebrowserUploadUrl(string $url): self
    {
        $this->config['filebrowserUploadUrl'] = $url;

        return $this;
    }

    // ==================== УНИВЕРСАЛЬНЫЕ МЕТОДЫ ====================

    /**
     * Устанавливает произвольный параметр конфигурации
     */
    public function setConfig(string $key, $value): self
    {
        $this->config[$key] = $value;

        return $this;
    }

    /**
     * Сливает произвольную конфигурацию с текущей
     */
    public function mergeConfig(array $config): self
    {
        $this->config = array_replace_recursive($this->config, $config);

        return $this;
    }

    /**
     * Заменяет конфигурацию полностью
     */
    public function replaceConfig(array $config): self
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Подготовка данных для view
     */
    protected function viewData(): array
    {
        // Дефолтная и кастомная конфигурации
        $config = array_replace_recursive($this->getDefaultConfig(), $this->config);

        // Отделяем LFM-конфигурацию, чтобы она не попала в основной конфиг CKEditor
        $lfmConfig = null;
        if (isset($config['lfmConfig'])) {
            $lfmConfig = $config['lfmConfig'];
            unset($config['lfmConfig']);
        }

        return [
          'editorConfigJson' => json_encode($config, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
          'lfmConfigJson'    => $lfmConfig ? json_encode($lfmConfig, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : '',
        ];
    }

}