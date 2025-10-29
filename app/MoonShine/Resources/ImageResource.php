<?php

declare(strict_types = 1);

namespace App\MoonShine\Resources;

use App\Models\Image;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\Hidden;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image as ImageField;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

class ImageResource extends ModelResource
{
    protected string $model = Image::class;

    protected string $title = 'Изображения';

    protected string $column = 'title';

    // Отключаем отображение в меню, так как это вспомогательный ресурс
//    protected bool $isAsync = true;

    public function getActiveActions(): array
    {
        return ['view', 'update', 'delete'];
    }

//    protected function pages(): array
//    {
//        return [
//          PageType::FORM,
//        ];
//    }

    protected function indexFields(): iterable
    {
        return [
          ID::make()->sortable(),

          ImageField::make('Изображение', 'path')
                    ->disk('public')
                    ->dir('images')
                    ->preview(),

          Text::make('Название', 'title'),

          Text::make('Тип', 'type')
              ->badge(fn($value) => match ($value) {
                  'main' => 'success',
                  'gallery' => 'primary',
                  default => 'secondary'
              }),

          Number::make('Сортировка', 'sort')
                ->sortable(),

          Switcher::make('Активно', 'is_active')
                  ->updateOnPreview(),
        ];
    }

    protected function formFields(): iterable
    {
        return [
          Box::make([
            ID::make()->readonly(),

            ImageField::make('Изображение', 'path')
                      ->disk('public')
                      ->dir('images/products')
                      ->removable()
                      ->required(),

            Text::make('Название', 'title')
                ->hint('Название изображения для внутреннего использования'),

            Text::make('Alt', 'alt')
                ->hint('Alt текст для SEO'),

//            Textarea::make('Описание', 'caption')->customAttributes([
//              'rows' => 6,
//            ]),

            Text::make('Тип', 'type')
                ->default('gallery')
                ->readonly(),

            Number::make('Сортировка', 'sort')
                  ->default(0)
                  ->hint('Порядок отображения'),

            Switcher::make('Активно', 'is_active')
                    ->default(true),

              // Скрытые поля для полиморфной связи
            Hidden::make('imageable_type'),
            Hidden::make('imageable_id'),
          ]),
        ];
    }

    protected function detailFields(): iterable
    {
        return $this->indexFields();
    }

    public function rules($item): array
    {
        return [
            /*'path' => ['required', 'string'],
            'title' => ['nullable', 'string', 'max:255'],
            'alt' => ['nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string'],
            'type' => ['nullable', 'string', 'max:50'],
            'sort' => ['nullable', 'integer'],
            'is_active' => ['boolean'],*/
        ];
    }
}