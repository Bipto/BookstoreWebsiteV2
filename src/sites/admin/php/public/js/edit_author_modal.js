function openAuthorModal(id) {
  const modal = new Modal('author-modal');
  modal.openModalWithFetch(
      '<h2>Edit Author</h2>', '/ajax/edit_author.php?id=' + id,
      '<button class="btn btn-primary" id="update-author-button">Save</button>');

  document.getElementById('update-author-button')
      .addEventListener('click', () => {
        alert('Updating author');
      })
}