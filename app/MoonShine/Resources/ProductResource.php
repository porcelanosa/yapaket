<?php

declare(strict_types = 1);

namespace App\MoonShine\Resources;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use MoonShine\Contracts\UI\ActionButtonContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\BelongsToMany;
use MoonShine\Laravel\Fields\Relationships\RelationRepeater;
use MoonShine\Laravel\Fields\Slug;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Support\Enums\Color;
use MoonShine\UI\Components\ActionButton;
use MoonShine\UI\Components\Badge;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Layout\Column;
use MoonShine\UI\Components\Layout\Grid;
use MoonShine\UI\Fields\Field;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;
use App\Helpers\ColorsHelper;
/**
 * @extends ModelResource<Product>
 */
class ProductResource extends ModelResource
{
    protected string $model = Product::class;

    protected string $title  = 'Пакеты';
    public string    $column = 'name';

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
                      $return.= Badge::make(
                        $value->name, ColorsHelper::getColorFromString($value->name)->value
                      )->style(['margin-right: 1rem; margin-bottom:.5rem;']);
                  }
                  return $return;
              }
            )
        ,
          BelongsToMany::make('Страницы', 'pages', PageResource::class)
            ->changePreview(
              function ($values) {
                  $return = '';
                  foreach ($values as $value) {
                      $return.= Badge::make($value->name, ColorsHelper::getColorFromString($value->name)->value);
                  }
                  return $return;
              }
            )
            ->relatedLink('products')
//            ->inLine(
//              separator: ' ',
//              badge: true
//            )
//            ->columnLabel('')
//                          ->fields([
//                            Text::make('Название', 'name'),
//                          ])
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
                  Text::make('Name', 'name')->reactive()->required(),
                  Slug::make('Заголовок браузера', 'title')->from('name')->unique()->live(),
                  Textarea::make('Meta Description', 'meta_description')->required(),
                  Textarea::make('Анонс', 'short_description')->required(),
                  Textarea::make('полное описание', 'description')->required(),
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
//                             ->selectMode()
                             ->horizontalMode(true, minColWidth: '100px', maxColWidth: '50%')
                             ->creatable(button: ActionButton::make('Добавить страницу', ''))
                             ->inLine(),
                RelationRepeater::make(
                  'Свойства',     // Заголовок
                  'attributes',     // Название связи в модели Product
                  resource: ProductAttributeResource::class,
                )->fields([
                  ID::make()->readonly()->style(['display: none']),
                  BelongsTo::make('Свойство', 'attribute', 'label'),
                  Text::make('Значение', 'value')->required(),
                ])
                  //->vertical()
                                ->removable()
//                                ->modifyItemButtons(
//                  fn(ActionButton $detail, $edit, $delete, $massDelete, HasMany $ctx) => [$edit, $delete, $massDelete],
//                )
//                  ->disableOutside()
                  //->withoutModals()
                  //->searchable()      // делаем поля searchable
                                ->creatable(),      // разрешаем создание новых прямо из формы
              ],

              colSpan        : 4,
              adaptiveColSpan: 12,
            ),
          ]),
        ];
    }

    /**
     * @return list<FieldContract>
     */
    protected function detailFields(): iterable
    {
        return [
          ID::make(),
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
}
