function moveCarousel(id, direction) {
  const carousel = document.getElementById(id);

  const scrollAmount = carousel.clientWidth * 0.4;

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

document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.carousel').forEach(carousel => {
    const id = carousel.id;

    updateCarouselButtons(id);

    carousel.addEventListener('scroll', () => {
      updateCarouselButtons(id);
    });
  });
});