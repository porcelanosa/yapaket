@props(['breadcrumbs'])

@if ($breadcrumbs && $breadcrumbs->isNotEmpty())
    <nav {{ $attributes->merge(['aria-label' => 'breadcrumb']) }}>
        <ol class="flex items-center space-x-1 text-sm text-gray-600">
            @foreach ($breadcrumbs as $breadcrumb)
                <li class="flex items-center">
                    @if (!$loop->last)
                        <a href="{{ $breadcrumb['url'] }}" class="hover:underline hover:text-red-600">
                            @if($breadcrumb['title']==='home')
                                <x-heroicon-o-home  class="w-4 h-4 inline mr-1 mb-0.5 text-gray-700"/>
                            @else
                                {{ $breadcrumb['title'] }}
                            @endif
                        </a>&nbsp;/
                    @else
                        <span class="font-bold text-gray-700" aria-current="page">
                            {{ $breadcrumb['title'] }}
                        </span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
