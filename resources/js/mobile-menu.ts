const menuToggle = document.getElementById('menu-toggle') as HTMLElement | null;
const menuClose = document.getElementById('menu-close') as HTMLElement | null;
const mobileMenu = document.getElementById('mobile-menu') as HTMLElement | null;
const menuOverlay = document.getElementById('menu-overlay') as HTMLElement | null;

function openMenu(): void {
    if (!mobileMenu || !menuOverlay || !menuToggle) return;
    
    mobileMenu.classList.add('active');
    menuOverlay.classList.add('active');
    menuToggle.classList.add('hamburger-active');
    document.body.style.overflow = 'hidden';
}

function closeMenu(): void {
    if (!mobileMenu || !menuOverlay || !menuToggle) return;
    
    mobileMenu.classList.remove('active');
    menuOverlay.classList.remove('active');
    menuToggle.classList.remove('hamburger-active');
    document.body.style.overflow = '';
}

// Добавляем обработчики только если элементы существуют
if (menuToggle) {
    menuToggle.addEventListener('click', openMenu);
}

if (menuClose) {
    menuClose.addEventListener('click', closeMenu);
}

if (menuOverlay) {
    menuOverlay.addEventListener('click', closeMenu);
}

document.addEventListener('keydown', (e: KeyboardEvent) => {
    if (e.key === 'Escape') {
        closeMenu();
    }
});