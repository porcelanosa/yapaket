const menuToggle = document.getElementById('menu-toggle');
const menuClose = document.getElementById('menu-close');
const mobileMenu = document.getElementById('mobile-menu');
const menuOverlay = document.getElementById('menu-overlay');

function openMenu () {
    mobileMenu.classList.add('active');
    menuOverlay.classList.add('active');
    menuToggle.classList.add('hamburger-active');
    document.body.style.overflow = 'hidden';
}

function closeMenu () {
    mobileMenu.classList.remove('active');
    menuOverlay.classList.remove('active');
    menuToggle.classList.remove('hamburger-active');
    document.body.style.overflow = '';
}

menuToggle.addEventListener('click', openMenu);
menuClose.addEventListener('click', closeMenu);
menuOverlay.addEventListener('click', closeMenu);
document.addEventListener('keydown',
    function(e) { if (e.key === 'Escape') { closeMenu(); } });