<?php

declare(strict_types = 1);

namespace App\MoonShine\Resources;

use App\Models\Menu;
use Illuminate\Contracts\Database\Eloquent\Builder;
use MoonShine\Contracts\UI\ActionButtonContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\HasMany;
use MoonShine\Laravel\Fields\Slug;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\ActionButton;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Text;

/**
 * @extends ModelResource<Menu>
 */
class MenuResource extends ModelResource
{
    protected string $model = Menu::class;

    protected string $title  = 'Меню';
    protected string $column = 'name';

    protected function modifyQueryBuilder(Builder $builder): Builder
    {
        return $builder
          ->with(['items']);
    }

    protected function modifyItemQueryBuilder(Builder $builder): Builder
    {
        return $builder
          ->with([
//            'categories',
//            'pages',
//            'attributes',
//            /*'primaryImage',
//            'productImages',*/
          ]);
    }
    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
          ID::make()->sortable(),
          Text::make('Название', 'name'),
          Text::make('Заголовок блока меню', 'title')->updateOnPreview(),
          Text::make('Комментарий', 'description')->updateOnPreview(),
          Slug::make('ЧПУ (URL)', 'slug'),
        ];
    }

    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function formFields(): iterable
    {
        return [
          Box::make([
            ID::make(),
            Text::make('Название', 'name')->reactive()->required(),
            Slug::make('ЧПУ (URL)', 'slug')->from('name')->unique()->live()->required(),
            Text::make('Заголовок блока меню', 'title')->hint('Если нужно, к примеру, в подвале'),
            Text::make('Описание блока меню', 'description')->hint('Исключительно для себя, чтобы не забыть где отображается это конкретное меню.'),

            HasMany::make('Пункты меню', 'items', resource: MenuItemResource::class)
                   // Указываем, какие поля отображать в таблице
                   ->fields([
                       ID::make()->sortable(),
                       BelongsTo::make('В меню', 'menu', formatted: 'name'),
                       Text::make('Название', 'title'),
                       Text::make('URL', 'uurl'),
                       Number::make('Порядок', 'order'),
                   ])
                   ->modifyItemButtons(
                     fn(ActionButton $detail, $edit, $delete, $massDelete, HasMany $ctx) => [$edit, $delete],
                   )
                   ->modifyCreateButton(
                     fn(ActionButton $button) => $button->setLabel('Добавить новый пункт в меню'),
                   )
                   ->modifyEditButton(
                     fn(ActionButton $button) => $button->setLabel('Изменить'),
                   )
                   // Добавляем кнопку для прикрепления существующих пунктов меню
                   ->indexButtons([
                       ActionButton::make('Прикрепить существующий пункт')
//                           ->modal('attach-menu-item')
                           ->icon('heroicons.outline.link')
                   ])
                   ->creatable(true),
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
     * @param  Menu  $item
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