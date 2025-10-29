<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\ProductImage;
use MoonShine\Contracts\Core\DependencyInjection\FieldsContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\Hidden;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image as ImageField;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use VI\MoonShineSpatieMediaLibrary\Fields\MediaLibrary;

class ProductImageResource extends ModelResource
{
    protected string $model = ProductImage::class;

    protected string $title = 'Изображения';

    protected string $column = 'title';

    protected array $with = ['product'];
    // Отключаем отображение в меню
    public bool $withPolicy = false;

//    public function getActiveActions(): array
//    {
//        return ['view', 'update', 'delete', 'create'];
//    }

    protected function indexFields(): iterable
    {
        return [
          ID::make()->sortable(),
//          BelongsTo::make('Product', 'product', resource: ProductResource::class),
          ImageField::make('Изображение', 'path')
                    ->disk('public')
                    ->dir('images/products')
//                    ->preview()
,
          Text::make('Название', 'title'),
          Text::make('Alt', 'alt'),
          Number::make('Сортировка', 'sort')
                ->sortable(),
          Switcher::make('Основное', 'is_primary')
                  ->updateOnPreview(),
        ];
    }

    protected function formFields(): iterable
    {
        return [
          Box::make([
            Hidden::make('product_id')->default(request('parent_id')),
            ImageField::make('Изображение', 'path')
//              MediaLibrary::make('Изображения', 'path')
                      ->disk('public')
                      ->dir('images/products')
                      ->removable()
                      ->allowedExtensions(['jpg', 'jpeg', 'png', 'gif', 'webp'])
                      /*->required()*/,
            Text::make('Название', 'title'),
            Text::make('Alt', 'alt'),
            Number::make('Сортировка', 'sort')->default(0),
            Switcher::make('Основное изображение', 'is_primary')->default(false),
          ]),
        ];
    }

    protected function detailFields(): iterable
    {
        return $this->indexFields();
    }

    public function search(): array
    {
        return [];
    }
    public function rules(mixed $item): array
    {
        return [
//          'path' => ['required', 'string'],
          'title' => ['nullable', 'string', 'max:255'],
          'alt' => ['nullable', 'string', 'max:255'],
          'sort' => ['nullable', 'integer'],
          'is_primary' => ['boolean'],
        ];
    }
//    public function save(mixed $item, ?FieldsContract $fields = null): mixed
//    {
//        ds($item, $fields);
//        return parent::save($item, $fields);
//    }
}