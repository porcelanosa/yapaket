<?php

namespace App\View\Components\Yp;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class YpButton extends Component
{
    public string $type;
    public ?string $href;
    public bool $outline;
    public bool $block;
    public string $baseClasses;

    public function __construct(
      string $type = 'button',
      ?string $href = null,
      bool $outline = false,
      bool $block = false
    ) {
        $this->type = $type;
        $this->href = $href;
        $this->outline = $outline;
        $this->block = $block;

        // Вычисляем базовые классы сразу
        $classes = [
          'inline-flex items-center justify-center',
          'font-medium rounded-sm',
          'transition duration-200',
          'hover:cursor-pointer',
          'px-4 py-2',
        ];

        if ($this->block) {
            $classes[] = 'w-full';
        }

        $this->baseClasses = implode(' ', $classes);
    }

    public function render() //: Factory|View|Htmlable|string|\Closure|\Illuminate\View\View
    {
        \Log::debug('YpButton class IS being used!');
        return view('components.yp.yp-button', ['baseClasses' => $this->baseClasses])->with([
          'baseClasses' => $this->baseClasses,
          'type' => $this->type,
          'href' => $this->href,
          'outline' => $this->outline,
          'block' => $this->block,
        ]);
    }
}