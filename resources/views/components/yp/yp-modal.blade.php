@props(['id', 'title' => null, 'icon' => null])

<div
        x-data="{ open: false }"
        x-on:open-modal.window="if ($event.detail === '{{ $id }}') open = true"
        x-on:close-modal.window="if ($event.detail === '{{ $id }}') open = false"
        @if($closeOnEscape)
            x-on:keydown.escape.window="open = false"
        @endif
        x-show="open"
        x-cloak
        {{ $attributes->merge(['class' => $baseClasses]) }}
>
    <div
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 backdrop-blur-none"
            x-transition:enter-end="opacity-100 backdrop-blur-sm"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 backdrop-blur-sm"
            x-transition:leave-end="opacity-0 backdrop-blur-none"
            @if($closeOnBackdrop)
                x-on:click="open = false"
            @endif
            class="fixed inset-0 bg-black/25 supports-[backdrop-filter]:bg-black/25 supports-[backdrop-filter]:backdrop-blur-sm z-10"
    ></div>

    {{-- Modal Content --}}
    <div
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="{{ $contentClasses }} {{ $attributes->get('class', '') }}"
            x-on:click.stop
    >
        {{-- Header --}}
        @if($title || $icon || isset($header))
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    @if($icon)
                        <div class="flex-shrink-0">
                            {!! $icon !!}
                        </div>
                    @endif

                    @if(isset($header))
                        {{ $header }}
                    @elseif($title)
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ $title }}
                        </h3>
                    @endif
                </div>

                {{-- Close Button --}}
                <button
                        type="button"
                        x-on:click="open = false"
                        class="text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-sm hover:bg-gray-100 hover:cursor-pointer"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        {{-- Body --}}
        <div class="px-6 py-4">
            {{ $slot }}
        </div>

        {{-- Footer --}}
        @if(isset($footer))
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-lg">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>