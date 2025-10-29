import PhotoSwipeLightbox from 'photoswipe/lightbox';
import 'photoswipe/style.css';

document.addEventListener('DOMContentLoaded', () => {
    const gallery = document.getElementById('post-gallery');
    if (!gallery) return;
    
    const lightbox = new PhotoSwipeLightbox({
        gallery: '#post-gallery',
        children: 'a.popup', // Теперь ищем все ссылки с классом popup
        pswpModule: () => import('photoswipe'),
        // Дополнительные настройки для лучшего UX
        wheelToZoom: true,
        padding: { top: 20, bottom: 20, left: 20, right: 20 }
    });
    
    // Кастомные подписи (опционально)
    lightbox.on('uiRegister', function() {
        lightbox.pswp.ui.registerElement({
            name: 'custom-caption',
            order: 9,
            isButton: false,
            appendTo: 'root',
            html: 'Caption text',
            onInit: (el, pswp) => {
                lightbox.pswp.on('change', () => {
                    const currSlideElement = lightbox.pswp.currSlide.data.element;
                    let captionHTML = '';
                    if (currSlideElement) {
                        const hiddenCaption = currSlideElement.querySelector('.hidden-caption-content');
                        if (hiddenCaption) {
                            captionHTML = hiddenCaption.innerHTML;
                        } else {
                            const img = currSlideElement.querySelector('img');
                            captionHTML = img ? img.getAttribute('alt') : '';
                        }
                    }
                    el.innerHTML = captionHTML || '';
                });
            }
        });
    });
    
    // Опционально: предзагрузка изображений
    // lightbox.on('beforeOpen', () => {
    //     lightbox.pswp.options.preload = [1, 2];
    // });
    
    lightbox.init();
});