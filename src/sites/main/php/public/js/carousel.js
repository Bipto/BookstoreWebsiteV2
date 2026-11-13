function moveCarousel(id, direction) {
  const carousel = document.getElementById(id);
  const firstItem = carousel.children[0];

  const itemWidth = firstItem.getBoundingClientRect().width;
  const gap = parseFloat(getComputedStyle(carousel).gap) || 0;

  const scrollAmount = itemWidth + gap;

  carousel.scrollBy({left: direction * scrollAmount, behavior: 'smooth'});
}

function updateCarouselButtons(id) {
  const carousel = document.getElementById(id);

  const container = carousel.parentElement;

  const buttons = container.querySelectorAll('.carousel-arrow');

  const previousButton = buttons[0];
  const nextButton = buttons[1];

  previousButton.disabled = carousel.scrollLeft <= 0;

  nextButton.disabled =
      carousel.scrollLeft + carousel.clientWidth >= carousel.scrollWidth - 1;
}

function registerCarouselCallbacks() {
  document.querySelectorAll('.carousel').forEach(carousel => {
    const id = carousel.id;

    updateCarouselButtons(id);

    carousel.addEventListener('scroll', () => {
      updateCarouselButtons(id);
    });
  });
}