// public/vendor/porcelanosa-ckeditor/ckeditor-init.js
document.addEventListener('alpine:init', () => {
    window.initCKEditorFromData = async (el) => {
        if (el._ckeditorInstance) {
            console.warn('CKEditor already initialized on this element', el);
            return;
        }
        
        try {
            const raw = el.dataset.editorConfig;
            if (!raw) {
                console.error('No editor config');
                return;
            }
            
            const config = JSON.parse(raw.trim() || '{}');
            
            const cssUrl = el.dataset.editorCss;
            if (cssUrl) {
                config.contentsCss = [cssUrl];
            }
            console.log('CKEditor config:', config);
            // LFM config
            let lfmConfig = null;
            const lfmRaw = el.dataset.lfmConfig;
            if (lfmRaw && lfmRaw.trim() !== '') {
                lfmConfig = JSON.parse(lfmRaw);
            }
            
            if (!window.CKEDITOR || !window.CKEDITOR.ClassicEditor) {
                console.error('CKEditor not loaded or wrong build (window.CKEDITOR.ClassicEditor missing).');
                console.debug('window.CKEDITOR keys:', Object.keys(window.CKEDITOR || {}));
                return;
            }
            
            const {
                ClassicEditor,
                Alignment,
                Bold,
                BlockQuote,
                Code,
                CodeBlock,
                Essentials,
                FontBackgroundColor,
                FontColor,
                FontFamily,
                FontSize,
                Heading,
                HorizontalLine,
                Italic,
                Link,
                List,
                TodoList,
                Paragraph,
                Strikethrough,
                Table,
                TableToolbar,
                Underline,
                Undo,
                Image,
                ImageCaption,
                ImageResize,
                ImageStyle,
                ImageToolbar,
                ImageUpload,
                FileRepository,
                SourceEditing,
                Indent,
                IndentBlock,
                MediaEmbed,
                // UI компоненты могут быть в разных местах
                ui
            } = window.CKEDITOR;
            
            // ПРАВИЛЬНЫЙ ПОИСК ButtonView для UMD-сборки
            let ButtonView = null;
            
            // Попробуем разные варианты расположения ButtonView
            if (window.CKEDITOR.ui && window.CKEDITOR.ui.ButtonView) {
                ButtonView = window.CKEDITOR.ui.ButtonView;
            } else if (window.CKEDITOR.ButtonView) {
                ButtonView = window.CKEDITOR.ButtonView;
            } else if (ui && ui.ButtonView) {
                ButtonView = ui.ButtonView;
            } else {
                // Последняя попытка - поиск в глобальных объектах CKEditor
                for (let key in window.CKEDITOR) {
                    if (window.CKEDITOR[key] && window.CKEDITOR[key].ButtonView) {
                        ButtonView = window.CKEDITOR[key].ButtonView;
                        break;
                    }
                }
            }
            
            console.log('ButtonView found:', ButtonView);
            
            config.plugins = config.plugins || [
                Alignment,
                Bold,
                BlockQuote,
                Code,
                CodeBlock,
                Essentials,
                FontBackgroundColor,
                FontColor,
                FontFamily,
                FontSize,
                Heading,
                HorizontalLine,
                Italic,
                Link,
                List,
                TodoList,
                Paragraph,
                Strikethrough,
                Table,
                TableToolbar,
                Underline,
                Undo,
                Image,
                ImageCaption,
                ImageResize,
                ImageStyle,
                ImageToolbar,
                ImageUpload,
                FileRepository,
                SourceEditing,
                Indent,
                IndentBlock,
            ];
            
            // Подключаем LFM Upload Adapter как extraPlugins (если есть lfmConfig)
            if (lfmConfig && window.LFMUploadAdapterPlugin) {
                config.extraPlugins = config.extraPlugins || [];
                config.extraPlugins.push(window.LFMUploadAdapterPlugin(lfmConfig));
            }

            // --- NEW PLUGIN-BASED APPROACH ---
            if (ButtonView) {
                // Define a plugin to add the LFM button
                function LfmButtonPlugin(editor) {
                    editor.ui.componentFactory.add('insertImageLFM', (locale) => {
                        const button = new ButtonView(locale);
                        button.set({
                            label: 'Выбрать из медиатеки',
                            // icon: '<svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M6.91 10.54c.26-.23.64-.21.88.03l3.36 3.14 2.23-2.06a.64.64 0 0 1 .87 0l2.52 2.97V4.5H3.2v10.12l3.71-4.08zm10.27-7.51c.6 0 1.09.47 1.09 1.05v11.84c0 .59-.49 1.06-1.09 1.06H2.79c-.6 0-1.09-.47-1.09-1.06V4.08c0-.58.49-1.05 1.1-1.05h14.38zm-5.22 5.56a1.96 1.96 0 1 1 3.4-1.96 1.96 1.96 0 0 1-3.4 1.96z"/></svg>',
                            icon: '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><path fill="#2dcc9f" d="M30 5.851v20.298H2V5.851z"/><path fill="#fff" d="M24.232 8.541a2.2 2.2 0 1 0 1.127.623a2.2 2.2 0 0 0-1.127-.623M18.111 20.1q-2.724-3.788-5.45-7.575L4.579 23.766h10.9q1.316-1.832 2.634-3.663M22.057 16q-2.793 3.882-5.584 7.765h11.169Q24.851 19.882 22.057 16"/></svg>',
                            withText: false,
                            tooltip: true,
                        });

                        button.on('execute', () => {
                            const prefix = lfmConfig?.prefix || '/laravel-filemanager';
                            const type = lfmConfig?.type || 'image';
                            if (window.openLFMPopup) {
                                window.openLFMPopup((url) => {
                                    if (url) {
                                        try {
                                            editor.model.change(writer => {
                                                const imageElement = writer.createElement('imageBlock', { src: url });
                                                editor.model.insertContent(imageElement, editor.model.document.selection);
                                            });
                                        } catch (err) {
                                            console.error('Insert image failed:', err);
                                        }
                                    }
                                }, type, prefix);
                            } else {
                                console.error('openLFMPopup not available');
                            }
                        });

                        return button;
                    });
                }

                // Add the plugin to the config
                if (!config.extraPlugins) {
                    config.extraPlugins = [];
                }
                config.extraPlugins.push(LfmButtonPlugin);

            } else {
                 function LfmCommandPlugin(editor) {
                     editor.commands.add('insertImageLFM', {
                        execute: () => {
                            const prefix = lfmConfig?.prefix || '/laravel-filemanager';
                            const type = lfmConfig?.type || 'image';
                            if (window.openLFMPopup) {
                                window.openLFMPopup((url) => {
                                    if (url) {
                                        try {
                                            editor.model.change(writer => {
                                                const imageElement = writer.createElement('imageBlock', { src: url });
                                                editor.model.insertContent(imageElement, editor.model.document.selection);
                                            });
                                        } catch (err) {
                                            console.error('Insert image failed (fallback):', err);
                                        }
                                    }
                                }, type, prefix);
                            } else {
                                console.error('openLFMPopup is not available');
                            }
                        }
                    });
                }
                 if (!config.extraPlugins) {
                    config.extraPlugins = [];
                }
                config.extraPlugins.push(LfmCommandPlugin);
                console.warn('ButtonView not available - using command only for LFM');
            }
            // --- END OF NEW APPROACH ---
            
            // Гарантируем, что toolbar.items — массив
            if (!config.toolbar) config.toolbar = { items: [] };
            if (!Array.isArray(config.toolbar.items)) config.toolbar.items = [];
            
            // Создаем редактор
            const editor = await ClassicEditor.create(el, config);

            // --- Подключаем CSS в iframe редактора ---
            if (cssUrl && editor.sourceElement.tagName === 'TEXTAREA') {
                const iframe = editor.ui.getEditableElement().closest('iframe');
                if (iframe) {
                    const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                    if (iframeDoc) {
                        const existing = iframeDoc.querySelector(`link[href="${cssUrl}"]`);
                        if (!existing) {
                            const link = iframeDoc.createElement('link');
                            link.rel = 'stylesheet';
                            link.href = cssUrl;
                            iframeDoc.head.appendChild(link);
                        }
                    }
                }
            }
            
            // Обновляем textarea value при изменениях
            editor.model.document.on('change:data', () => {
                el.value = editor.getData();
                el.dispatchEvent(new Event('input', { bubbles: true }));
            });
            
            el._ckeditorInstance = editor;
            
        } catch (e) {
            console.error('CKEditor init error:', e);
        }
    };
    
    Alpine.data('ckeditor', () => ({ init() {} }));
});

window.getCKEditorInstance = (el) => el?._ckeditorInstance || null;
