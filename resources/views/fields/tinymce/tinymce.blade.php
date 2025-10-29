<x-moonshine::form.textarea
        :attributes="$attributes->except(['x-bind:id', ':id'])->merge(['id' => 'tmcecontent-' . $column])"
>{!! $value ?? '' !!}</x-moonshine::form.textarea>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        tinymce.init({
            selector: 'textarea#tmcecontent-{{$column}}',
            plugins: 'code table lists',
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table',
            menu: {
                file: { title: 'File', items: 'newdocument | print' },
                edit: { title: 'Edit', items: 'undo redo | cut copy paste' },
                insert: { title: 'Insert', items: 'link | table' },
                view: { title: 'View', items: 'visualaid' },
                format: { title: 'Format', items: 'bold italic' },
                tools: { title: 'Tools', items: 'wordcount' }
            }
        });
    });
</script>