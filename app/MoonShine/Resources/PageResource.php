<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Page;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Fields\Slug;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Laravel\Fields\Relationships\BelongsToMany;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

class PageResource extends ModelResource
{
    protected string $model = Page::class;

    protected string $title = 'Страницы';

    protected string    $column = 'name';

    public function title(): string
    {
        return 'name';
    }
    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
          ID::make()->sortable(),
          Text::make('Название', 'name')->sortable(),
            BelongsToMany::make('Кол-во продуктов', 'products', resource: ProductResource::class)
                         ->relatedLink('pages')/*->onlyCount()*/,
          Switcher::make('Активна', 'active')->updateOnPreview()
        ];
    }
    public function formFields(): array
    {
        return [
          ID::make()->sortable(),
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
