<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductAttribute;

use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Fields\Text;

/**
 * @extends ModelResource<ProductAttribute>
 */
class ProductAttributeResource extends ModelResource
{
    protected string $model = ProductAttribute::class;

    protected string $title = 'Product Attributes';
    
    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
          BelongsTo::make('Атрибут', 'attribute', 'label'), // 👈 здесь указываем relation
          Text::make('Value', 'value'),
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
                  BelongsTo::make('Product', 'product'), // связь с продуктами
                  BelongsTo::make('Свойство', 'attribute', 'label'), // связь со справочником атрибутов
                  Text::make('Значение', 'value')->required(),
            ])
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
     * @param ProductAttribute $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [];
    }
}
