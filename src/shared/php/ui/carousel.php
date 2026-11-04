<?php

require_once '/var/www/shared/utils/text_utils.php';

interface Control
{
    public function render(): string;
}

class Carousel
{
    public function __construct(
        private string $title,
        private array $controls,
        private string $id = 'carousel'
    ) {}

    public function render(): string
    {
        ob_start();
?>

        <section class="carousel-section">

            <h2 class="carousel-heading">
                <?= htmlspecialchars($this->title) ?>
            </h2>

            <div class="carousel-container">

                <button
                    class="carousel-arrow"
                    onclick="moveCarousel('<?= htmlspecialchars($this->id, ENT_QUOTES) ?>', -1)"
                    aria-label="Previous items"
                    disabled>
                    &#10094;
                </button>

                <div
                    class="carousel"
                    id="<?= htmlspecialchars($this->id, ENT_QUOTES) ?>">

                    <?php

                    foreach ($this->controls as $control) {
                        echo $control->render();
                    }

                    ?>

                </div>

                <button
                    class="carousel-arrow"
                    onclick="moveCarousel('<?= htmlspecialchars($this->id, ENT_QUOTES) ?>', 1)"
                    aria-label="Next items">
                    &#10095;
                </button>

            </div>

        </section>

<?php

        return ob_get_clean();
    }
}
