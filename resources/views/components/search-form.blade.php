{{-- resources/views/components/search-form.blade.php --}}
@props(['id' => 'site-search', 'class' => ''])

<form {{ $attributes->merge(['class' => $class, 'role' => 'search', 'action' => route('search')]) }}>
    <label for="{{ $id }}" class="sr-only">Поиск по сайту</label>
    <input id="{{ $id }}" type="text" name="q" placeholder="Искать на сайте"
           class="w-full border-2 border-dashed border-gray-400 px-3 py-2 text-sm focus:outline-red-400 focus:outline-2 focus:outline-dashed focus:border-transparent">
</form>
