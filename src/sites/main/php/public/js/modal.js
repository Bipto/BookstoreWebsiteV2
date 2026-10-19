export class Modal {
  constructor(id) {
    this.modal = document.getElementById(id);
    this.modalClose = document.getElementById(id + '-close');
    this.modalOverlay = document.querySelector('.' + id + '-overlay');
    this.modalBody = document.getElementById(id + '-body');
    this.modalSpinner = document.getElementById(id + '-spinner');
    this.modalHeader = document.getElementById(id + '-title');
    this.modalFooter = document.getElementById(id + '-footer');


    this.modalClose.addEventListener('click', () => this.closeModal());

    this.modalOverlay.addEventListener('click', () => this.closeModal());

    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape' && !this.modal.hidden) {
        this.closeModal();
      }
    });
  }

  openModal(header, content, footer) {
    this.modal.hidden = false;

    document.body.style.overflow = 'hidden';

    // Hide spinner
    this.modalSpinner.style.display = 'none';

    this.modalHeader.innerHTML = header;

    // Insert returned HTML
    this.modalBody.innerHTML = content;

    this.modalFooter.innerHTML = footer;
  }

  clear() {
    // Clear previous book
    this.modalBody.innerHTML = '';

    // Show spinner
    this.modalSpinner.style.display = 'flex';
  }

  closeModal() {
    this.modal.hidden = true;

    document.body.style.overflow = '';
  }
}


/* document.addEventListener('click', async (event) => {
  const book = event.target.closest('.book-link');

  if (!book) {
    return;
  }

  const bookId = book.dataset.bookId;

  const modal = new Modal('book-modal');

  try {
    const response = await fetch(
        `/ajax/books/view_book_modal.php?id=${encodeURIComponent(bookId)}`);

    if (!response.ok) {
      throw new Error(`HTTP error: ${response.status}`);
    }

    const data = await response.json();
    const header = data['header'];
    const content = data['content'];
    const footer = data['footer'];

    modal.openModal(header, content, footer);

  } catch (error) {
    console.error('Failed to load book:', error);

    const body = `
        <div class="book-error">
            <h2>Unable to load book</h2>
            <p>
                There was a problem retrieving the book information.
            </p>
        </div>
    `;

    modal.openModal('', body, '');
  }
}); */