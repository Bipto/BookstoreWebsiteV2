function openAuthorModal(id) {
  const modal = new Modal('modal');
  modal.openModalWithFetch(
      '<h2>Edit Author</h2>', '/ajax/edit_author.php?id=' + id,
      '<button class="btn btn-primary" type="submit" id="update-author-button" form="edit-author-form">Save</button>',
      initialiseImagePreview);
}

function initialiseImagePreview() {
  const imageInput = document.getElementById('image');
  const imagePreview = document.getElementById('author-image-preview');

  if (!imageInput || !imagePreview) return;

  imageInput.addEventListener('change', function() {
    const file = this.files[0];

    if (file) {
      const imageUrl = URL.createObjectURL(file);

      imagePreview.src = imageUrl;
    }
  });
}

document.addEventListener('submit', async function(e) {
  // Get the form that was submitted
  const form = e.target;

  // Only handle the edit author form
  if (form.id !== 'edit-author-form') return;

  e.preventDefault();

  const formData = new FormData(form);
  const message = document.getElementById('message');

  try {
    const response = await fetch(
        '/ajax/update_author.php', {method: 'POST', body: formData});

    if (!response.ok) {
      throw new Error('Server returned an error: ' + response.status);
    }

    const modal = new Modal('modal');
    modal.closeModal();
  } catch (error) {
    console.error(error);
  }
});