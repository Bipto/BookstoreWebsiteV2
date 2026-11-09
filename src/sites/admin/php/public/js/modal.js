class Modal {
  constructor(id) {
    this.modal = document.getElementById(id);
    this.modalClose = document.getElementById(id + '-close');
    this.modalOverlay = document.querySelector('.' + id + '-overlay');
    this.modalBody = document.getElementById(id + '-body');
    this.modalHeader = document.getElementById(id + '-title');
    this.modalFooter = document.getElementById(id + '-footer');


    this.modalClose.addEventListener('click', () => this.closeModal());

    this.modal.addEventListener('click', (event) => {
      if (event.target === this.modal) {
        this.closeModal();
      }
    });

    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape' && !this.modal.hidden) {
        this.closeModal();
      }
    });
  }

  async loadContent(url) {
    this.modalBody.innerHTML =
        '<div class="spinner-overlay-rel"><div class="spinner" ></div></div>';

    try {
      const response = await fetch(url);

      if (!response.ok) {
        throw new Error(`HTTP error: ${response.status}`);
      }

      const html = await response.text();

      this.modalBody.innerHTML = html;

    } catch (error) {
      console.error('Error loading modal content:', error);

      this.modalBody.innerHTML =
          '<p>Sorry, something went wrong loading the content.</p>';
    }
  }

  async openModalWithFetch(header, url, footer = '') {
    this.modal.hidden = false;
    document.body.style.overflow = 'hidden';

    this.modalHeader.innerHTML = header;
    this.modalFooter.innerHTML = footer;

    await this.loadContent(url);
  }

  openModal(header, content, footer) {
    this.modal.hidden = false;

    document.body.style.overflow = 'hidden';

    this.modalHeader.innerHTML = header;

    // Insert returned HTML
    this.modalBody.innerHTML = content;

    this.modalFooter.innerHTML = footer;
  }

  clear() {
    // Clear previous book
    this.modalBody.innerHTML = '';
  }

  closeModal() {
    this.modal.hidden = true;

    document.body.style.overflow = '';
  }
}