function openAuthorModal(id) {
  const modal = new Modal('author-modal');
  modal.openModal(
      '<h3>Edit author(' + id + ')</h3>',
      '<div class="spinner-overlay-rel"><div class="spinner"></div></div>', '');
}