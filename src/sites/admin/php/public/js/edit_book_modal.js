function openBookModal(id) {
  const modal = new Modal('modal');
  modal.openModalWithFetch(
      '<h2>Edit Book</h2>', '/ajax/edit_book.php?id=' + id,
      '<button class="btn btn-primary" type="submit" id="update-book-button" form="edit-book-form">Save</button>',
      initialiseImagePreview);
}

function initialiseImagePreview() {
  const imageInput = document.getElementById('image');
  const imagePreview = document.getElementById('book-image-preview');

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
  if (form.id !== 'edit-book-form') return;

  e.preventDefault();

  const formData = new FormData(form);
  const message = document.getElementById('message');

  try {
    const response =
        await fetch('/ajax/update_book.php', {method: 'POST', body: formData});

    if (!response.ok) {
      throw new Error('Server returned an error: ' + response.status);
    }

    const modal = new Modal('modal');
    modal.closeModal();
  } catch (error) {
    console.error(error);
  }
});