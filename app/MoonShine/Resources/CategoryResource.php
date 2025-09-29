<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Fields\Slug;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\BelongsToMany;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

class CategoryResource extends ModelResource
{
    protected string $model = Category::class;

    protected string $title = 'Категории';

    protected string    $column = 'name';

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
          ID::make()->sortable(),
          Text::make('Name', 'name')->sortable(),
          BelongsTo::make('Родительская категория', 'parent', resource: self::class)
                   ->nullable()->sortable(),
            Switcher::make('Активна', 'active')->updateOnPreview()
        ];
    }

    public function formFields(): array
    {
        return [
          ID::make()->sortable(),

          BelongsTo::make('Родительская категория', 'parent', resource: self::class)
                   ->nullable(),

          Text::make('Название', 'name')->required(),
          Text::make('Заголовок', 'title'),
          Slug::make('Slug', 'slug')->from('name')->separator('-'),
          Textarea::make('Meta description', 'meta_description'),
          Textarea::make('Краткое описание', 'short_description'),
          Textarea::make('Описание', 'description'),

          Number::make('Сортировка', 'sort')->default(0),
          Switcher::make('Активна', 'active')->default(true),

//          BelongsToMany::make('Продукты', 'products', resource: ProductResource::class),
        ];
    }

    public function rules(mixed $item): array
    {
        return [
          'name' => ['required', 'string', 'max:255'],
          'slug' => ['nullable', 'string', 'max:255'],
        ];
    }
}
