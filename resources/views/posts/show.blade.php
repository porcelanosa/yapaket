@php
    use Illuminate\Support\Facades\Storage;
    $is_media = !empty($media);
@endphp
@extends('layouts.mainLayout')
@section('title')

@endsection
@section('meta_description')
    {{ $post->meta_description ?? $post->announce ?? $post->short_description }}
@endsection

@section('content')
    <section>
        <div class="mx-auto max-w-screen-xl py-8  lg:px-8">
            <div class="grid grid-cols-1 gap-4 {{ $media ? 'md:grid-cols-2 md:items-center md:gap-8' : '' }}">
                <div class="{{ $media ? '' : 'col-span-full' }}">
                    <div class="max-w-prose md:max-w-none">
                        <h1 class="mt-1">
                            {{ $post->name ?? '' }}
                        </h1>

                        <p class="mt-4 text-gray-700">
                            {{ $post->announce ?? $post->short_description }}
                        </p>
                    </div>
                </div>

                @if($media)
                    <div>
                        <div class="pswp-gallery" id="post-gallery">
                            <a href="{{ $media->getUrl('original_post_webp') }}"
                               data-pswp-width="{{ $media->width ?? 1200 }}"
                               data-pswp-height="{{ $media->height ?? 800 }}"
                               data-cropped="true"
                               target="_blank"
                               rel="noopener"
                               class="popup w-full h-auto rounded-md"
                            >
                                {{ $media('original_post_webp', ['class' => 'w-full h-auto rounded-md', 'alt'=> $post->announce ?? "Post image"]) }}
                                {{--<img src="{{ $media->getUrl('original_post_webp') }}"
                                     alt="{{ $post->announce ?? 'Post image' }}"
                                     loading="lazy"
                                     class="w-full h-auto rounded-md"
                                >--}}
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
    <section>
        {!! $post->content !!}
    </section>
@endsection
@push('scripts')
    @vite('resources/js/post-gallery.js')
@endpush