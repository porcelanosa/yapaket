<?php
declare(strict_types = 1);

namespace App\MoonShine\Fields;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Support\DTOs\FileItem;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Traits\Fields\FileDeletable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaLibrary extends Image
{
    protected function prepareFill(array $raw = [], mixed $casted = null): mixed
    {
        $value = $casted->getOriginal()->getMedia($this->column);

        if (!$this->isMultiple()) {
            $value = $value->first();
        }

        return $value;
    }

    public function getFullPathValues(): array
    {
        $values = $this->value;

        if (!$values) {
            return [];
        }

        if ($this->isMultiple()) {
            // Проверяем, что $values - это коллекция
            if (!$values instanceof Collection) {
                return [];
            }
            return $values->map(fn($media): string => $media->getFullUrl())->toArray();
        }

        // Для одиночного значения проверяем наличие метода getFullUrl
        if (method_exists($values, 'getFullUrl')) {
            return [$values->getFullUrl()];
        }

        return [];
    }

    protected function resolveOnApply(): ?Closure
    {
        return static fn($item) => $item;
    }

    protected function resolveAfterApply(mixed $data): mixed
    {
        $requestValue = $this->getRequestValue();
        // Проверяем, был ли загружен новый файл
        $hasNewFile = $requestValue instanceof UploadedFile;

        // Сценарий 1: Загружен новый файл (создание или замена)
        if ($hasNewFile) {
            // Полностью очищаем коллекцию от старых файлов
            $data->clearMediaCollection($this->column);
            // Добавляем новый файл
            $this->addMedia($data, $requestValue);

            return null;
        }

        // Сценарий 2: Новый файл не загружен. Проверяем, остались ли старые.
        // Наличие значения в скрытом поле означает, что старый файл нужно оставить.
        $hiddenValues = request()->input($this->getHiddenRemainingValuesKey(), []);
        $hasOldFile = !empty($hiddenValues);

        // Если новый файл не загружен И старый файл был удален в интерфейсе (скрытое поле пустое),
        // то это сценарий удаления.
        if (!$hasNewFile && !$hasOldFile) {
            $data->clearMediaCollection($this->column);
        }

        // Если новый файл не загружен, но старый остался — ничего делать не нужно.

        return null;
    }

    protected function resolveAfterDestroy(mixed $data): mixed
    {
        $data
          ->getMedia($this->column)
          ->each(fn(Media $media) => $media->delete());

        return $data;
    }

    private function addMedia(HasMedia $item, UploadedFile $file): Media
    {
        return $item->addMedia($file)
                    ->preservingOriginal()
                    ->toMediaCollection($this->column);
    }

    protected function getFiles(): Collection
    {
        $values = $this->toValue();

        return collect($this->getFullPathValues())
          ->mapWithKeys(function (string $path, int $index) use ($values): array {
              // Получаем правильное значение для rawValue
              if ($this->isMultiple() && $values instanceof Collection) {
                  $rawValue = (string)($values->get($index)?->id ?? $path);
              } elseif ($values instanceof Media) {
                  $rawValue = (string)($values->id ?? $path);
              } else {
                  $rawValue = $path;
              }

              return [
                $index => new FileItem(
                  fullPath: $path,
                  rawValue: $rawValue,
                  name: (string) \call_user_func($this->resolveNames(), $path, $index, $this),
                  attributes: \call_user_func($this->resolveItemAttributes(), $path, $index, $this),
                ),
              ];
          });
    }

    public function removeExcludedFiles(): void
    {
        $currentValue = $this->toValue(withDefault: false);
        $newValue = $this->getValue();

        // Получаем список текущих файлов
        $currentFiles = collect();
        if ($currentValue instanceof Collection) {
            $currentFiles = $currentValue;
        } elseif ($currentValue instanceof Media) {
            $currentFiles = collect([$currentValue]);
        }

        // Получаем список новых файлов
        $newFiles = collect();
        if (is_array($newValue)) {
            $newFiles = collect($newValue);
        } elseif ($newValue instanceof Media) {
            $newFiles = collect([$newValue]);
        } elseif (is_string($newValue)) {
            $newFiles = collect([$newValue]);
        }

        // Удаляем файлы, которых нет в новом списке
        $currentFiles->each(function ($file) use ($newFiles): void {
            if ($file instanceof Media) {
                $shouldDelete = !$newFiles->contains(fn($newFile) =>
                  ($newFile instanceof Media && $newFile->id === $file->id) ||
                  (is_string($newFile) && $newFile === $file->getFullUrl())
                );

                if ($shouldDelete) {
                    $file->delete();
                }
            }
        });
    }

    public function removeExcludedFilesPatched(null|array|string $newValue = null): void
    {
        $currentValue = $this->toValue(withDefault: false);

        if (!$currentValue) {
            return;
        }

        // Нормализуем новое значение в массив
        $newFiles = array_filter(is_array($newValue) ? $newValue : [$newValue]);

        // Преобразуем текущее значение в коллекцию
        $currentFiles = collect();
        if ($currentValue instanceof Collection) {
            $currentFiles = $currentValue;
        } elseif ($currentValue instanceof Media) {
            $currentFiles = collect([$currentValue]);
        }

        // Удаляем файлы
        $currentFiles->each(function ($file) use ($newFiles): void {
            if ($file instanceof Media && !in_array($file->id, $newFiles, true)) {
                $file->delete();
            }
        });
    }

    public function getRequestValue(int|string|null $index = null): mixed
    {
        return $this->prepareRequestValue(
          $this->getCore()->getRequest()->getFile(
            $this->getRequestNameDot($index),
          ) ?? false
        );
    }

    public function apply(Closure $default, mixed $data): mixed
    {
        // Просто возвращаем исходную модель, не вызывая родительский метод apply().
        // Это предотвращает попытку записать NULL в несуществующую колонку.
        $item = $default($data);

        // На всякий случай удаляем свойство из объекта модели,
        // чтобы оно гарантированно не попало в SQL-запрос.
        unset($item->{$this->column});

        return $item;
    }
}