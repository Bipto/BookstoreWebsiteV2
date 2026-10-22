const filterToggle = document.querySelector('.book-filters-toggle');
const filters = document.querySelector('.book-filters');

if (filterToggle && filters) {
  filterToggle.addEventListener('click', () => {
    const isOpen = filterToggle.getAttribute('aria-expanded') === 'true';

    filterToggle.setAttribute('aria-expanded', String(!isOpen));

    filters.classList.toggle('is-open', !isOpen);
  });
}