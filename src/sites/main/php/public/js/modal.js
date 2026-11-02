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