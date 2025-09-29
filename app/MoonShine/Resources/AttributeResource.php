<?php

declare(strict_types = 1);

namespace App\MoonShine\Resources;

use App\Models\Attribute;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;

/**
 * @extends ModelResource<Attribute>
 */
class AttributeResource extends ModelResource
{
    protected string $model = Attribute::class;

    protected string $title = 'Атрибуты';

    public string $column = 'label';
    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
          ID::make()->sortable(),
          Text::make('Свойство', 'name'),
          Text::make('Название свойства', 'label'),
        ];
    }

    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function formFields(): iterable
    {
        return [
          Box::make([
            ID::make()->disabled(),
            Text::make('Name', 'name')->required(),
            Text::make('Label', 'label'),
          ]),
        ];
    }

    /**
     * @return list<FieldContract>
     */
//    protected function detailFields(): iterable
//    {
//        return [
//          ID::make(),
//        ];
//    }

    /**
     * @return array<string, string[]|string>
     *
     * @param  Attribute  $item
     *
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [];
    }
}
