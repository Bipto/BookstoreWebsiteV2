function openAuthorModal(id) {
  const modal = new Modal('author-modal');
  /* modal.openModal(
      '', '<div class="spinner-overlay-rel"><div class="spinner"></div></div>',
      ''); */

  modal.openModalWithFetch(
      '<h2>Edit Author</h2>', '/ajax/test_fetch.php?id=' + id,
      '<button>Close</button>');
}