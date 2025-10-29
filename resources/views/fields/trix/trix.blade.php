<div>
    <trix-toolbar id="trix-toolbar-{{ $column }}"></trix-toolbar>
    <div class="hidden">
        <x-moonshine::form.textarea
                :attributes="$attributes->except(['x-bind:id', ':id'])->merge(['id' => 'trix-' . $column])"
        >{!! $value ?? '' !!}</x-moonshine::form.textarea>
    </div>
    <trix-editor input="trix-{{ $column }}" toolbar="trix-toolbar-{{ $column }}" class="trix-editor"></trix-editor>
</div>

@if($useLfm)
    <script>
        // Инициализация до загрузки DOM для настройки тулбара
        (function() {
            if (window.Trix) {
                // Настройка доступных уровней заголовков
                Trix.config.blockAttributes.heading = {
                    'heading1': { tagName: 'h1', terminal: true, breakOnReturn: true, group: false },
                    'heading2': { tagName: 'h2', terminal: true, breakOnReturn: true, group: false },
                    'heading3': { tagName: 'h3', terminal: true, breakOnReturn: true, group: false }
                };

                // Установка дефолтного блока как <p>
                Trix.config.blockAttributes.default = {
                    tagName: 'p',
                    breakOnReturn: true,
                    group: false
                };

                // Кастомизация тулбара
                const originalGetDefaultHTML = Trix.config.toolbar.getDefaultHTML;

                Trix.config.toolbar.getDefaultHTML = function() {
                    const defaultHTML = originalGetDefaultHTML.call(this);
                    console.log('Original defaultHTML:', defaultHTML);
                    return defaultHTML.replace(
                        /<button[^>]*data-trix-attribute="heading1"[^>]*>/,
                        `<select class="trix-custom-heading-select" data-trix-button-group="block-tools">
                            <option value="p">Paragraph</option>
                            <option value="heading1">H1</option>
                            <option value="heading2">H2</option>
                            <option value="heading3">H3</option>
                        </select>`
                    );
                };
            }
        })();

        document.addEventListener('DOMContentLoaded', () => {
            console.log('Trix:', window.Trix);

            const observer = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    mutation.addedNodes.forEach((node) => {
                        if (node.nodeType === Node.ELEMENT_NODE && node.matches('trix-editor:not([data-init])')) {
                            const editor = node;
                            editor.dataset.init = 'true';
                            console.log('Инициализируем редактор', editor);

                            editor.addEventListener('trix-before-initialize', (e) => console.log('trix-before-initialize', e));
                            editor.addEventListener('trix-initialize', (e) => {
                                console.log('trix-initialize', e);
                                queueMicrotask(() => addLfmButton(editor));
                                queueMicrotask(() => addImageResizeHandler(editor));
                                initializeHeadingSelect(editor);
                            });
                            editor.addEventListener('trix-before-render', (e) => console.log('trix-before-render', e));
                            editor.addEventListener('trix-attachment-add', (e) => console.log('trix-attachment-add', e));
                            editor.addEventListener('trix-selection-change', () => addResizeHandleIfSelected(editor));
                        }
                    });
                });
            });

            observer.observe(document.body, { childList: true, subtree: true });

            // Проверяем уже существующие редакторы
            document.querySelectorAll('trix-editor:not([data-init])').forEach((editor) => {
                editor.dataset.init = 'true';
                console.log('Инициализируем существующий редактор', editor);

                editor.addEventListener('trix-before-initialize', (e) => console.log('trix-before-initialize', e));
                editor.addEventListener('trix-initialize', (e) => {
                    console.log('trix-initialize', e);
                    queueMicrotask(() => addLfmButton(editor));
                    queueMicrotask(() => addImageResizeHandler(editor));
                    initializeHeadingSelect(editor);
                });
                editor.addEventListener('trix-before-render', (e) => console.log('trix-before-render', e));
                editor.addEventListener('trix-attachment-add', (e) => console.log('trix-attachment-add', e));
                editor.addEventListener('trix-selection-change', () => addResizeHandleIfSelected(editor));
            });

            // Нормализация блоков при изменении
            document.addEventListener('trix-change', (e) => {
                const editor = e.target.editor;
                const doc = editor.getDocument();
                doc.getBlocks().forEach((block, index) => {
                    if (block.isListItem() && typeof block.text === 'string' && !block.text.trim()) {
                        editor.setDocument(doc.removeBlockAtIndex(index));
                    }
                    if (block.tagName === 'div' && !block.text.match(/<figure/)) {
                        editor.setBlockAttributes(index, { tagName: 'p' });
                    }
                });
            });
        });

        function initializeHeadingSelect(editorEl) {
            const toolbarId = editorEl.getAttribute('toolbar');
            const toolbar = document.getElementById(toolbarId);
            if (!toolbar) return;

            const select = toolbar.querySelector('.trix-custom-heading-select');
            if (!select) {
                console.log('Heading select not found in toolbar');
                return;
            }

            select.addEventListener('change', (e) => {
                const value = e.target.value;
                const editor = editorEl.editor;
                console.log('Selected heading:', value);
                console.log('Editor :', editor);
                if (editor) {
                    editor.activateAttribute(value);
                    editor.deactivateAttribute('heading1'); // Сбрасываем предыдущий заголовок
                    console.log('Applied heading:', value);
                }
            });
        }

        function addLfmButton(editorEl) {
            console.log('addLfmButton called for editorEl:', editorEl);
            console.log('editor instance:', editorEl.editor);

            const editor = editorEl.editor;
            if (!editor) {
                console.log('No editor instance, retrying...');
                requestAnimationFrame(() => addLfmButton(editorEl));
                return;
            }

            const toolbarId = editorEl.getAttribute('toolbar');
            let toolbar = toolbarId ? document.getElementById(toolbarId) : editorEl.toolbarElement || editor.toolbar?.element;
            console.log('Toolbar:', toolbar);

            if (!toolbar) {
                console.log('No toolbar found, retrying...');
                requestAnimationFrame(() => addLfmButton(editorEl));
                return;
            }

            if (toolbar.querySelector('.trix-button--lfm')) {
                console.log('LFM button already exists');
                return;
            }

            const group = toolbar.querySelector('.trix-button-group--file-tools');
            if (!group) {
                console.log('No suitable button group found');
                return;
            }

            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'trix-button trix-button--icon trix-button--lfm trix-button--no-toggle';
            button.title = 'Загрузить из файлового менеджера';
            button.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                </svg>
            `;
            button.setAttribute('data-trix-action', 'x-lfm');

            const fieldId = editorEl.getAttribute('input');
            const fileType = "{{ $lfmConfig['type'] }}";

            group.appendChild(button);
            console.log('LFM button added to group');

            editorEl.addEventListener('trix-action-invoke', (event) => {
                if (event.actionName === 'x-lfm') {
                    console.log('LFM action invoked');
                    openLfmPopup(fileType, fieldId, editorEl);
                }
            });
        }

        function addImageResizeHandler(editorEl) {
            editorEl.addEventListener('trix-change', () => {
                const figures = editorEl.querySelectorAll('figure.attachment');
                figures.forEach(figure => {
                    if (!figure.querySelector('.resize-handle') && figure.querySelector('img')) {
                        addResizeHandle(figure);
                    }
                });
            });
        }

        function addResizeHandleIfSelected(editorEl) {
            const selection = window.getSelection();
            if (selection.rangeCount > 0) {
                const range = selection.getRangeAt(0);
                let node = range.commonAncestorContainer;
                // Поднимаемся к элементу, если это текстовый узел
                while (node && node.nodeType === Node.TEXT_NODE) {
                    node = node.parentNode;
                }
                const figure = node ? node.closest('figure.attachment') : null;
                if (figure && !figure.querySelector('.resize-handle') && figure.querySelector('img')) {
                    addResizeHandle(figure);
                }
            }
        }

        function addResizeHandle(figure) {
            const existingHandle = figure.querySelector('.resize-handle');
            if (existingHandle) return; // Избегаем дублирования

            const resizeHandle = document.createElement('div');
            resizeHandle.className = 'resize-handle';
            Object.assign(resizeHandle.style, {
                position: 'absolute',
                bottom: '0',
                right: '0',
                width: '10px',
                height: '10px',
                background: '#007bff',
                cursor: 'se-resize',
                zIndex: '1001',
                borderRadius: '50%',
                boxShadow: '0 0 5px rgba(0, 0, 0, 0.3)'
            });

            resizeHandle.addEventListener('mousedown', startResize);

            function startResize(e) {
                e.preventDefault();
                const startX = e.clientX;
                const startY = e.clientY;
                const img = figure.querySelector('img');
                const startWidth = parseInt(img.style.width, 10) || img.naturalWidth || 100;
                const startHeight = parseInt(img.style.height, 10) || img.naturalHeight || 100;

                function doResize(moveEvent) {
                    const dx = moveEvent.clientX - startX;
                    const dy = moveEvent.clientY - startY;
                    const newWidth = Math.max(50, startWidth + dx);
                    const newHeight = Math.max(50, startHeight + (dy * (startHeight / startWidth))); // Сохранение пропорций
                    img.style.width = `${newWidth}px`;
                    img.style.height = `${newHeight}px`;
                }

                function stopResize() {
                    document.removeEventListener('mousemove', doResize);
                    document.removeEventListener('mouseup', stopResize);
                }

                document.addEventListener('mousemove', doResize);
                document.addEventListener('mouseup', stopResize);
            }

            // Временно делаем figure редактируемым для добавления
            const wasEditable = figure.getAttribute('contenteditable');
            if (wasEditable === 'false') {
                figure.setAttribute('contenteditable', 'true');
            }
            figure.appendChild(resizeHandle);
            if (wasEditable === 'false') {
                figure.setAttribute('contenteditable', 'false');
            }

            console.log('Resize handle added to figure:', figure);
        }

        function openLfmPopup(fileType, fieldId, editorEl) {
            const prefix = "{{ trim($lfmConfig['prefix'], '/') }}";
            const routePrefix = '/' + prefix;
            const url = `${routePrefix}?type=${fileType}`;
            console.log('Opening LFM popup with URL:', url);

            window.open(url, 'FileManager', 'width=900,height=600');

            window.SetUrl = (items) => {
                console.log('SetUrl called with items:', items);
                if (!items || !items.length) {
                    console.log('No items selected');
                    return;
                }

                const editor = editorEl.editor;
                if (!editor) {
                    console.log('Editor not ready');
                    return;
                }

                const allowedTypes = @json($lfmConfig['allowedFileTypes']);
                const maxSize = {{ $lfmConfig['maxUploadSize'] ?? 'null' }};
                const maxDimension = {{ $lfmConfig['maxImageDimension'] ?? 'null' }};
                const minDimension = {{ $lfmConfig['minImageDimension'] ?? 'null' }};

                items.forEach((item) => {
                    const fileUrl = item.url;
                    console.log('Processing file:', fileUrl);

                    let mimeType = item.is_image ? 'image/jpeg' : 'application/octet-stream';
                    if (item.name) {
                        const extension = item.name.split('.').pop().toLowerCase();
                        if (['jpg', 'jpeg', 'png', 'gif'].includes(extension)) {
                            mimeType = `image/${extension === 'jpg' ? 'jpeg' : extension}`;
                        }
                    }

                    if (allowedTypes.length && !allowedTypes.includes(mimeType)) {
                        console.log('File type not allowed:', mimeType);
                        return;
                    }

                    if (maxSize && item.size && item.size > maxSize * 1024) {
                        console.log('File too large:', item.size);
                        return;
                    }

                    if (fileType === 'image' && item.is_image && (maxDimension || minDimension)) {
                        const img = new Image();
                        img.src = fileUrl;
                        img.onload = () => {
                            if (maxDimension && (img.width > maxDimension || img.height > maxDimension)) {
                                console.log('Image dimensions too large:', img.width, img.height);
                                return;
                            }
                            if (minDimension && (img.width < minDimension || img.height < minDimension)) {
                                console.log('Image dimensions too small:', img.width, img.height);
                                return;
                            }
                            const attachment = new Trix.Attachment({
                                content: `<figure class="image"><img src="${fileUrl}" width="100%" height="auto" alt="${item.name}"></figure>`
                            });
                            editor.insertAttachment(attachment);
                            console.log('Content inserted for:', fileUrl);
                        };
                        img.onerror = () => console.log('Failed to load image:', fileUrl);
                    } else if (fileType === 'image' && item.is_image) {
                        const attachment = new Trix.Attachment({
                            content: `<figure class="image"><img src="${fileUrl}" width="100%" height="auto" alt="${item.name}"></figure>`
                        });
                        editor.insertAttachment(attachment);
                        console.log('Content inserted for:', fileUrl);
                    } else {
                        editor.insertHTML(`<p><a href="${fileUrl}" target="_blank" class="attachment">Файл: ${item.name}</a></p>`);
                        console.log('Content inserted for:', fileUrl);
                    }
                });
            };
        }
    </script>

    <style>
        .resize-handle {
            display: block;
            background: #007bff;
            border-radius: 50%;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
        }

        figure.attachment {
            position: relative;
            display: inline-block;
        }

        /* Убедимся, что resize-handle виден над другими элементами */
        figure.attachment img {
            max-width: 100%;
            vertical-align: middle;
        }
    </style>
@endif