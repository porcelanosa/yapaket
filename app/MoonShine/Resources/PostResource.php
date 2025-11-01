<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\MoonShine\Fields\TinyMCE;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;
use App\MoonShine\Fields\MediaLibrary;
//use MoonShine\CKEditor\Fields\CKEditor;
use App\MoonShine\Fields\CKEditor;
use MoonShine\Contracts\UI\ActionButtonContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Fields\Slug;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

/**
 * @extends ModelResource<Post>
 */
class PostResource extends ModelResource
{
    protected string $model = Post::class;

    protected string $title  = 'Новости';
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
          MediaLibrary::make('Изображение', 'post_image'),
          Text::make('Название', 'name')->sortable(),
          Text::make('Заголовок', 'title')->sortable(),
          Switcher::make('Активна', 'active')->updateOnPreview(),
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
            Text::make('Заголовок (title)', 'title')->required(),
            Textarea::make('Meta Description', 'meta_description'),
            Textarea::make('Краткое описание', 'announce'),
            CKEditor::make('Контент', 'content')
                ->withFileManager()
                ->placeholderText('Содержимое статьи ...'),
//                    ->language('ru')

//            TinyMCE::make('Content', 'content')
//                ->useLaravelFileManager()
//                ->lfmType('image')
//                ->lfmPath('articles'),
            MediaLibrary::make('Изображение', 'post_image')->removable(),
            Switcher::make('Активна', 'active')->default(1),
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
     * @param  Post  $item
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
