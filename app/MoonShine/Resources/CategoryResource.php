<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Category;
use App\MoonShine\Fields\MediaLibrary;
use App\MoonShine\Pages\CategoryIndexPage;
use Illuminate\Database\Eloquent\Model;
use Leeto\MoonShineTree\Resources\TreeResource;
use MoonShine\Contracts\UI\ActionButtonContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Fields\Slug;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\BelongsToMany;
use MoonShine\Support\Enums\PageType;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

class CategoryResource extends TreeResource
{
    protected string $model = Category::class;

    protected string $title = 'Категории';

    protected string    $column = 'name';
    protected bool $createInModal = true;

    protected bool $editInModal = true;

    protected array $with = ['parent', 'children'];

    protected string $sortColumn = 'sort';

    protected ?PageType $redirectAfterSave = PageType::INDEX;

    protected function pages(): array
    {
        return [
          CategoryIndexPage::class,
          FormPage::class,
          DetailPage::class,
        ];
    }
    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
          ID::make()->sortable(),
          BelongsTo::make('Родительская категория', 'parent', resource: self::class)
                   ->nullable()
//                   ->sortable()
            ,
          Text::make('Name', 'name')
            //  ->sortable()
            ,
            Switcher::make('Активна', 'active')->updateOnPreview()
        ];
    }

    public function formFields(): array
    {
        return [
          ID::make()->sortable(),

          BelongsTo::make('Родительская категория', 'parent', resource: self::class)
                   ->nullable(),

          Text::make('Название', 'name')->reactive()->required(),
          Slug::make('Slug', 'slug')
              ->from('name')
              ->unique()
              ->live()->required(),
          Text::make('Заголовок', 'title')->required(),
          Textarea::make('Meta description', 'meta_description'),
          Textarea::make('Краткое описание', 'short_description'),
          Textarea::make('Описание', 'description'),

//          Number::make('Сортировка', 'sort')->default(0),
          Switcher::make('Активна', 'active')->default(true),

          MediaLibrary::make('Изображение', 'category_image')
                      ->removable(),
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
    protected function modifyDetailButton(ActionButtonContract $button): ActionButtonContract
    {
        return $button->emptyHidden();
    }
    public function treeKey(): ?string
    {
        return 'parent_id';
    }

    public function sortKey(): string
    {
        return $this->getSortColumn();
    }
}
