document.addEventListener('DOMContentLoaded', function() {
  const profile = document.querySelector('.profile');
  const profileCircle = document.querySelector('#profile-circle');
  const profileDropdown = document.querySelector('#profile-dropdown');

  const cart = document.querySelector('.cart');
  const cartCircle = document.querySelector('#cart-circle');
  const cartDropdown = document.querySelector('#cart-dropdown');

  const cartContent = document.querySelector('#cart-content');
  const cartSpinner = document.querySelector('#cart-spinner');

  const hamburger = document.querySelector('#hamburger');
  const navLinks = document.querySelector('#links');

  // Profile
  profileCircle.addEventListener('click', function(event) {
    event.stopPropagation();

    profile.classList.toggle('open');
    profileDropdown.classList.toggle('show');

    cart.classList.remove('open');
    cartDropdown.classList.remove('show');
  });

  // Close dropdowns when clicking outside
  document.addEventListener('click', function(event) {
    if (!event.target.closest('.profile, #profile-dropdown')) {
      profile.classList.remove('open');
      profileDropdown.classList.remove('show');
    }

    if (!event.target.closest('.cart, #cart-dropdown')) {
      cart.classList.remove('open');
      cartDropdown.classList.remove('show');
    }
  });

  // Hamburger
  hamburger.addEventListener('click', function() {
    const isOpen = hamburger.classList.toggle('open');

    navLinks.classList.toggle('show', isOpen);

    hamburger.setAttribute('aria-expanded', isOpen);
    hamburger.setAttribute(
        'aria-label',
        isOpen ? 'Close navigation menu' : 'Open navigation menu');
  });
});