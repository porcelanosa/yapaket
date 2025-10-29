{{-- resources/views/components/left-sidebar.blade.php --}}
@props(['menuCategories'=>[]])

<aside {{ $attributes->merge(['class' => 'bg-white shadow-md rounded-lg p-4']) }}>
    <x-category-menu />
{{--    @foreach($menuCategories as $category)--}}
{{--        <div class="mb-6">--}}
{{--            <h3 class="font-bold text-red-600 border-b">--}}
{{--                <a href="{{ $item['url'] }}" class="text-gray-700 hover:text-red-600">--}}
{{--                    {{ $category['title'] }}--}}
{{--                </a>--}}
{{--            </h3>--}}
{{--            <ul class="space-y-2 text-sm">--}}
{{--                @foreach($category['items'] as $item)--}}
{{--                    <li>--}}
{{--                        <a href="{{ $item['url'] }}" class="text-gray-700 hover:text-red-600">--}}
{{--                            {{ $item['title'] }}--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                @endforeach--}}
{{--            </ul>--}}
{{--        </div>--}}
{{--    @endforeach--}}
</aside>
