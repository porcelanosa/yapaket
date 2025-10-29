// Laravel File Manager для CKEditor 5

window.openLFMPopup = function(callback, type = 'image', prefix = '/laravel-filemanager') {
    const url = `${prefix}?type=${type}`;
    const w = 900, h = 600;
    const left = (screen.width / 2) - (w / 2);
    const top = (screen.height / 2) - (h / 2);
    
    const popup = window.open(url, 'FileManager', `width=${w},height=${h},top=${top},left=${left}`);
    
    if (!popup) {
        alert('Разрешите popup окна для этого сайта');
        return false;
    }

    window.SetUrl = function(items) {
        try {
            if (Array.isArray(items)) {
                items.forEach(item => item?.url && callback(item.url));
            } else if (items?.url) {
                callback(items.url);
            }
        } finally {
            popup?.close();
        }
    };

    return false;
};

class LFMUploadAdapter {
    constructor(loader, lfmConfig) {
        this.loader = loader;
        this.lfmConfig = lfmConfig || {};
    }

    upload() {
        return new Promise((resolve, reject) => {
            const prefix = this.lfmConfig.prefix || '/laravel-filemanager';
            const type = this.lfmConfig.type || 'image';
            
            window.openLFMPopup((url) => {
                url ? resolve({ default: url }) : reject('No URL');
            }, type, prefix);
        });
    }

    abort() {}
}

window.LFMUploadAdapterPlugin = function(lfmConfig) {
    return function(editor) {
        editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
            return new LFMUploadAdapter(loader, lfmConfig);
        };
    };
};

window.insertImageToEditor = function(editor, imageUrl) {
    if (!editor || !imageUrl) return;
    
    try {
        editor.model.change(writer => {
            const imageElement = writer.createElement('imageBlock', { src: imageUrl });
            editor.model.insertContent(imageElement, editor.model.document.selection);
        });
    } catch (e) {
        console.error('Insert image failed:', e);
    }
};
