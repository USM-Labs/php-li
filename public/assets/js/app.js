const toggle = document.querySelector('[data-menu-toggle]');
const menu = document.querySelector('[data-menu]');

if (toggle && menu) {
    toggle.addEventListener('click', () => {
        menu.classList.toggle('open');
    });
}

document.querySelectorAll('input[type="date"]').forEach((input) => {
    const today = new Date().toISOString().slice(0, 10);
    input.min = today;
});

