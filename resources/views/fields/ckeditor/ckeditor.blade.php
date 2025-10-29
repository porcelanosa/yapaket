{{--@dump($editorConfigJson, $lfmConfigJson)--}}
<x-moonshine::form.textarea
        ::id="$id('ckeditor')"
        x-data="ckeditor()"
        x-init="initCKEditorFromData($el)"
        data-editor-config='{{ $editorConfigJson }}'
        data-lfm-config='{{ $lfmConfigJson }}'
        data-editor-css="{{ Vite::asset('resources/css/app.css') }}"
        :attributes="$attributes"
>{!! $value ?? '' !!}</x-moonshine::form.textarea>
<style>
.ck-button-insertImageLFM {
display: inline-block !important;
visibility: visible !important;
}
</style>