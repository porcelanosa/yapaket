document.addEventListener('alpine:init', () => {
    Alpine.data('ckeditor', (config) => ({
        init() {
            CKEDITOR.ClassicEditor
            .create(this.$el, config)
            .then(editor => {
                editor.model.document.on('change:data', () => {
                    this.$el.value = editor.getData();
                });
            })
            .catch(error => {
                console.error('CKEditor initialization failed:', error);
            });
        },
    }));
});