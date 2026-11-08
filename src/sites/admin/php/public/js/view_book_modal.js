import {Modal} from './modal.js'

document.addEventListener('click', async (event) => {
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
});