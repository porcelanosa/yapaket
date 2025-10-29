<?php

declare(strict_types = 1);

namespace App\View\Components\Yp;

use Illuminate\View\Component;

class YpModal extends Component
{
    public string $id;
    public ?string $title;
    public ?string $icon;
    public string $size;
    public bool $closeOnBackdrop;
    public bool $closeOnEscape;
    public string $baseClasses;
    public string $contentClasses;

    public function __construct(
      string $id,
      ?string $title = null,
      ?string $icon = null,
      string $size = 'md',
      bool $closeOnBackdrop = true,
      bool $closeOnEscape = true
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->icon = $icon;
        $this->size = $size;
        $this->closeOnBackdrop = $closeOnBackdrop;
        $this->closeOnEscape = $closeOnEscape;

        // Базовые классы для контейнера модалки
        $this->baseClasses = implode(' ', [
          'fixed inset-0 z-40',
          'flex items-center justify-center',
          'p-4 sm:p-6 lg:p-8',
        ]);

        // Классы для контента модалки с учетом размера
        $sizeMap = [
          'sm' => 'max-w-sm',
          'md' => 'max-w-md',
          'lg' => 'max-w-lg',
          'xl' => 'max-w-xl',
          '2xl' => 'max-w-2xl',
          '3xl' => 'max-w-3xl',
          'full' => 'max-w-full',
        ];

        $this->contentClasses = implode(' ', [
          'bg-white rounded-lg shadow-xl',
          'w-full z-50',
          $sizeMap[$this->size] ?? $sizeMap['md'],
          'transform transition-all',
        ]);
    }

    public function render()
    {
        return view('components.yp.yp-modal');
    }
}