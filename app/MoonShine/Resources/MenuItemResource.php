<?php

declare(strict_types = 1);

namespace App\MoonShine\Resources;

use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Contracts\Database\Eloquent\Builder;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\MorphTo;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Text;

/**
 * @extends ModelResource<MenuItem>
 */
class MenuItemResource extends ModelResource
{
    protected string $model = MenuItem::class;

    protected string $title  = 'Пункты меню';
    protected string $column = 'title';

    protected function modifyQueryBuilder(Builder $builder): Builder
    {
        return $builder
          ->with(['menu', 'parent', 'menuable', 'children']);
    }

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
          ID::make()->sortable(),
          BelongsTo::make('В меню', 'menu', resource: MenuResource::class)->sortable(),
          Text::make('Название', 'title')->updateOnPreview()->required(),
          Text::make('URL', 'url'),
          Number::make('Порядок', 'order')->updateOnPreview(),
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
//            ID::make('menu_id'),
            BelongsTo::make('Родительский пункт', 'parent', resource: self::class)
                     ->nullable()
                     ->searchable(),
            Text::make('Название', 'title')->required(),
            Text::make('URL', 'url')
                ->hint('Используйте, если не выбрана связанная модель'),
            Text::make('Маршрут', 'route')
                ->hint('Имя маршрута в Laravel'),
            Number::make('Порядок', 'order')
                  ->default(0),
            BelongsTo::make('Меню', 'menu', resource: MenuResource::class)
                     ->required()
                     ->searchable(),
            MorphTo::make('Связанная модель', 'menuable')
                   ->types([
                     Post::class => ['title', 'Новости'],
                     Page::class => ['title', 'Страницы'],
                   ])->nullable()
                   ->default(null),
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
          Text::make('Название', 'title'),
        ];
    }

    /**
     * @return array<string, string[]|string>
     *
     * @param  MenuItem  $item
     *
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [];
    }
}
