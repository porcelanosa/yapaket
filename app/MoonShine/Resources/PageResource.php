<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Page;
use MoonShine\Contracts\UI\ActionButtonContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Fields\Relationships\BelongsToMany;
use MoonShine\Laravel\Fields\Slug;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;
use App\MoonShine\Fields\MediaLibrary;
use App\MoonShine\Fields\CKEditor;

class PageResource extends ModelResource
{
    protected string $model = Page::class;

    protected string $title = 'Страницы';
    protected string $column = 'name';

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
          MediaLibrary::make('Изображение', 'page_image'),
          Text::make('Название', 'name')->sortable(),
          Text::make('Заголовок', 'title')->sortable(),
          Switcher::make('На главной', 'show_in_main')->updateOnPreview(),
          Switcher::make('Активна', 'active')->updateOnPreview(),
            Number::make('Порядок', 'sort')
              ->buttons()
                  ->min(0)->step(10)->sortable()->updateOnPreview()
        ];
    }

    public function formFields(): array
    {
        return [
          ID::make()->sortable(),
          Text::make('Название', 'name')->reactive()->required(),
          Text::make('Заголовок', 'title')->required(),
          Slug::make('ЧПУ (URL)', 'slug')
              ->from('name')
              ->unique()
              ->live()
              ->separator('-')
              ->required(),
          Textarea::make('Meta description', 'meta_description'),
          Textarea::make('Анонс', 'short_description'),

          CKEditor::make('Содержимое страницы', 'description')
                  ->withFileManager()
                  ->placeholderText('Содержимое страницы ...'),

          Number::make('Сортировка', 'sort')->default(0),
          Switcher::make('Активна', 'active')->default(true),
          Switcher::make('На главной', 'show_in_main')->default(false),
          MediaLibrary::make('Изображение', 'page_image')
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
}
