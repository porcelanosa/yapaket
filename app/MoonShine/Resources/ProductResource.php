<?php

declare(strict_types = 1);

namespace App\MoonShine\Resources;

use App\Helpers\ColorsHelper;
use App\Models\Product;
use App\MoonShine\Fields\CKEditor;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Leeto\InputExtensionCharCount\InputExtensions\CharCount;
use MoonShine\Contracts\UI\ActionButtonContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\BelongsToMany;
use MoonShine\Laravel\Fields\Relationships\HasMany;
use MoonShine\Laravel\Fields\Relationships\RelationRepeater;
use MoonShine\Laravel\Fields\Slug;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\ActionButton;
use MoonShine\UI\Components\Badge;
use MoonShine\UI\Components\Collapse;
use MoonShine\UI\Components\Heading;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Layout\Column;
use MoonShine\UI\Components\Layout\Flex;
use MoonShine\UI\Components\Layout\Grid;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

/**
 * @extends ModelResource<Product>
 */
class ProductResource extends ModelResource
{
    protected string $model = Product::class;
//    protected array $with = ['attributes', 'categories', 'pages'];
    protected string $title  = 'Пакеты';
    public string    $column = 'name';


    protected function modifyQueryBuilder(Builder $builder): Builder
    {
        return $builder
          ->with(['categories', 'pages']);
    }

    protected function modifyItemQueryBuilder(Builder $builder): Builder
    {
        return $builder
          ->with([
            'attributes.attribute',
          ]);
    }

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
          ID::make()->sortable(),
          Text::make('Название', 'name')->sortable(),
          BelongsToMany::make('Категории', 'categories', CategoryResource::class)
                       ->changePreview(
                         function ($values, $ctx) {
                             $return = '';
                             foreach ($values as $value) {
                                 $return .= Badge::make(
                                   $value->name,
                                   ColorsHelper::getColorFromString($value->name)->value,
                                 )->style(['margin-right: 1rem; margin-bottom:.5rem;']);
                             }

                             return $return;
                         },
                       )
            ,
          BelongsToMany::make('Страницы', 'pages', PageResource::class)
                       ->changePreview(
                         function ($values) {
                             $return = '';
                             foreach ($values as $value) {
                                 $return .= Badge::make($value->name, ColorsHelper::getColorFromString($value->name)->value);
                             }

                             return $return;
                         },
                       )
                       ->relatedLink('products'),
        ];
    }

    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function formFields(): iterable
    {
        return [
          Grid::make([
            Column::make(
              [
                Box::make([
                  ID::make()->disabled(),

                  Flex::make([
                    Text::make('Название', 'name')->reactive()->required(),
                    Switcher::make('На сайте', 'status'),
                    Switcher::make('На главной', 'is_hit'),
                  ])
                      ->name('flex-titles')
                      ->justifyAlign('between')
                      ->itemsAlign('stretch'),
                  Collapse::make('ЧПУ и SEO', [
//                    Heading::make(),
                    Flex::make([
                      Text::make('Заголовок браузера', 'title')->required(),
                      Slug::make('ЧПУ (URL)', 'slug')->from('name')->unique()->live(),
                      Text::make('Meta Description', 'meta_description')
                          ->extension(new CharCount()),
                    ])
//                        ->name('flex-titles')
                        ->justifyAlign('between')
                        ->itemsAlign('end'),
                  ]),

                  Collapse::make('Цена и тираж:', [
//                    Heading::make('Цена и тираж:'),
                    Flex::make([
                      Text::make('Цена от:', 'price')->withoutWrapper(),
                      Text::make('Тираж от:', 'circulation')->withoutWrapper(),
                    ])
                        ->name('flex-titles')
                        ->justifyAlign('start')
                        ->itemsAlign('start'),
                  ]),
                  Textarea::make('Анонс', 'short_description')->required(),
                  CKEditor::make('Полное описание', 'description')->withFileManager()->placeholderText('Полное описание товара ...'),
                ],
                ),
              ],
              colSpan        : 8,
              adaptiveColSpan: 12,
            ),
            Column::make(
              [
                  // 🔗 Связь с категориями
                BelongsToMany::make('Категории', 'categories', resource: CategoryResource::class)
                             ->setLabel('Категории')
                             ->tree('parent_id')
                             ->creatable(button: ActionButton::make('Добавить категорию', '')),

                  // 🔗 Связь со страницами
                BelongsToMany::make('Страницы', 'pages', resource: PageResource::class)
                             ->creatable(button: ActionButton::make('Добавить страницу', ''))
                             ->searchable()
                             ->selectMode(),
                RelationRepeater::make(
                  'Свойства',     // Заголовок
                  'attributes',     // Название связи в модели Product
                  resource: ProductAttributeResource::class,
                )->fields([
                  ID::make(),
                  BelongsTo::make('Свойство', 'attribute', 'label'),
                  Text::make('Значение', 'value')->required(),
                ])
                                ->removable()
                                ->creatable(),
              ],

              colSpan        : 4,
              adaptiveColSpan: 12,
            ),
          ]),
//          Box::make('Галерея изображений', [
////            RelationRepeater::make('Изображения', 'images', resource: ProductImageResource::class)
//            RelationRepeater::make(
//              '',     // Заголовок
//              'productImages',     // Название связи в модели Product
//              resource: ProductImageResource::class,
//            )
//                            ->fields([
//                              ID::make()->readonly(),
//                              Image::make('Изображение', 'path')
//                                   ->disk('public')
//                                   ->dir('images/products')
//                                   ->removable()
//                                   ->required()
//                                ->changeFill(function (ProductImage $data, Image $field) {
//                                    // return $data->images->pluck('file');
//                                    // или raw
//                                    return $data->path;
//                                })
//                                   ->onApply(function (ProductImage $data): ProductImage {
//                                       return $data;
//                                   })
//                                   ->onAfterApply(function (ProductImage $data, false|array $values, Image $field) {
//                                       Log::info('Data: ', $data->toArray());
//
////                                       return $data;
//                                   }),
//
//                              Text::make('Название', 'title'),
//                              Text::make('Alt', 'alt'),
////                              Textarea::make('Описание', 'caption'),
//                              Number::make('Сортировка', 'sort')->default(0),
//                              Switcher::make('Главное', 'is_primary')->default(true),
//                            ])
//                            ->creatable(limit: 20)
//                            ->removable()
////                            ->reorderable('sort')
////                            ->vertical(),
//
////            MorphMany::make(
////              'Галерея',
////              'images',
////              resource: ImageResource::class
////            )->creatable()
//          ]),
          HasMany::make('Изображения', 'productImages', resource: ProductImageResource::class)
//                 ->searchable(false)
//                 ->async()
                 ->modifyItemButtons(
                   fn(ActionButton $detail, $edit, $delete, $massDelete, HasMany $ctx) => [$edit, $delete],
                 )
                 ->modifyCreateButton(
                   fn(ActionButton $button) => $button->setLabel('Добавить изображение в галерею'),
                 )
                 ->modifyEditButton(
                   fn(ActionButton $button) => $button->setLabel('Изменить'),
                 )
                 ->creatable(true),

          BelongsToMany::make('Похожие товары', 'relatedProducts', resource: self::class)
                       ->selectMode()
                       ->searchable(),
        ];
    }

    /**
     * @return list<FieldContract>
     */
    protected function detailFields(): iterable
    {
        return [
//          ID::make(),
        ];
    }

    /**
     * @return array<string, string[]|string>
     *
     * @param  Product  $item
     *
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [];
    }

    protected function modifyDetailButton(ActionButtonContract $button): ActionButtonContract
    {
        return $button->emptyHidden();
    }
}
