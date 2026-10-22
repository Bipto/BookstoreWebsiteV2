<?php

class ModalBuilder
{
    public static function build(string $id): string
    {
        return <<<HTML
        <div id="$id" class="modal" hidden>

            <div class="$id-overlay"></div>

            <div
                class="modal-content"
                role="dialog"
                aria-modal="true"
                aria-labelledby="$id-title">

                <header class="modal-header">
                    <div id="$id-title"></div>

                    <button
                        id="$id-close"
                        class="modal-close"
                        type="button"
                        aria-label="Close">
                        &times;
                    </button>
                </header>

                <main id="$id-body" class="modal-body">
                    <div id="$id-spinner" class="modal-spinner">
                        <div class="spinner"></div>
                    </div>
                </main>

                <footer class="modal-footer" id="$id-footer">
                </footer>

            </div>

        </div>
        
        HTML;
    }
}
