<?php

declare(strict_types = 1);

namespace App\View\Components\Yp;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class YpBadge extends Component
{
    public string $color;
    public string $rounded;
    public string $baseClasses;
    public string $colorClasses;
    public string $roundedClasses;

    public function __construct(
      string $color = 'gray',
      string $rounded = 'sm',
    ) {
        $this->color   = $color;
        $this->rounded = $rounded;

        // Базовые классы для всех badge
        $this->baseClasses = implode(' ', [
          'inline-flex items-center justify-center',
          'px-3 py-1',
          'text-sm font-semibold',
          'w-fit whitespace-nowrap shrink-0',
          'gap-1',
          'transition-colors duration-200',
          'cursor-pointer',
          '[&>svg]:size-5 [&>svg]:pointer-events-none',
        ]);

        // Карта цветов для badge
        $colorMap = [
          'red'    => 'bg-red-600 text-white hover:bg-red-700 [&>svg]:text-white',
          'green'  => 'bg-green-600 text-white [&>svg]:text-white ',
          'blue'   => 'bg-blue-600 text-white [&>svg]:text-white ',
          'yellow' => 'bg-yellow-500 text-gray-700 [&>svg]:text-gray-700 ',
          'gray'   => 'bg-gray-400 text-black [&>svg]:text-gray-700 ',
          'light-gray'   => 'bg-gray-200 text-gray-700 hover:bg-gray-300 [&>svg]:text-gray-700',
          'orange' => 'bg-orange-600 text-white [&>svg]:text-white ',
          'purple' => 'bg-purple-600 text-white [&>svg]:text-white ',
          'pink'   => 'bg-pink-600 text-white [&>svg]:text-white ',
        ];

        $this->colorClasses = $colorMap[$this->color] ?? $colorMap['gray'];

        // Карта скругленности
        $roundedMap = [
          'sm' => 'rounded',      // 4px (0.25rem) в Tailwind
          'lg' => 'rounded-xl',   // 12px (0.75rem) в Tailwind
        ];

        $this->roundedClasses = $roundedMap[$this->rounded] ?? $roundedMap['sm'];
    }

    public function render(): View|Factory|Htmlable|string|\Closure|\Illuminate\View\View
    {
        return view('components.yp.yp-badge');
    }
}
