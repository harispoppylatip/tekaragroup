const menuToggle = document.querySelector('[data-menu-toggle]');
const menu = document.querySelector('[data-menu]');

if (menuToggle && menu) {
    const setMenuOpen = (isOpen) => {
        menu.classList.toggle('hidden', !isOpen);
        menuToggle.setAttribute('aria-expanded', String(isOpen));
        menuToggle.querySelector('[data-menu-icon="open"]').classList.toggle('hidden', isOpen);
        menuToggle.querySelector('[data-menu-icon="close"]').classList.toggle('hidden', !isOpen);
    };

    menuToggle.addEventListener('click', () => setMenuOpen(menu.classList.contains('hidden')));
    menu.querySelectorAll('[data-menu-link]').forEach((link) => link.addEventListener('click', () => setMenuOpen(false)));
}

const filterButtons = document.querySelectorAll('[data-filter]');
const projectCards = document.querySelectorAll('[data-project]');

filterButtons.forEach((button) => {
    button.addEventListener('click', () => {
        const selected = button.dataset.filter;

        filterButtons.forEach((other) => other.setAttribute('aria-pressed', String(other === button)));
        projectCards.forEach((card) => {
            const categories = card.dataset.categories.split(' ');
            card.hidden = selected !== 'semua' && !categories.includes(selected);
        });
    });
});

document.querySelectorAll('[data-print]').forEach((button) => {
    button.addEventListener('click', () => window.print());
});

/**
 * Repeater rows (pengalaman, pendidikan, keahlian, tautan).
 * New rows take max(existing index) + 1 so they never collide with rows
 * whose keys are no longer sequential after a removal.
 */
document.querySelectorAll('[data-repeater]').forEach((repeater) => {
    const list = repeater.querySelector('[data-repeater-list]');
    const template = repeater.querySelector('[data-repeater-template]');

    const nextIndex = () => {
        const indexes = [...list.querySelectorAll('[data-repeater-row]')].map((row) => Number(row.dataset.index));

        return indexes.length ? Math.max(...indexes) + 1 : 0;
    };

    repeater.querySelector('[data-repeater-add]')?.addEventListener('click', () => {
        const html = template.innerHTML.replaceAll('__INDEX__', String(nextIndex()));
        list.insertAdjacentHTML('beforeend', html);
        list.lastElementChild?.querySelector('input, textarea')?.focus();
    });

    list.addEventListener('click', (event) => {
        const removeButton = event.target.closest('[data-repeater-remove]');

        if (removeButton) {
            removeButton.closest('[data-repeater-row]').remove();
        }
    });
});

document.querySelectorAll('form[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        if (!window.confirm(form.dataset.confirm)) {
            event.preventDefault();
        }
    });
});

const panelMenuToggle = document.querySelector('[data-panel-menu-toggle]');
const panelSidebar = document.querySelector('[data-panel-sidebar]');

panelMenuToggle?.addEventListener('click', () => {
    const isOpen = panelSidebar.classList.toggle('max-lg:hidden') === false;
    panelMenuToggle.setAttribute('aria-expanded', String(isOpen));
});
