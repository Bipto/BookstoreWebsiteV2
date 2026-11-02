<?php

require_once '/var/www/shared/utils/text_utils.php';

class Carousel
{
    private string $title;
    private array $items;
    private string $id;

    public function __construct(
        string $title,
        array $items,
        string $id = 'carousel'
    ) {
        $this->title = $title;
        $this->items = $items;
        $this->id = $id;
    }

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

                    <?php foreach ($this->items as $item): ?>

                        <div class="carousel-card">

                            <img
                                src="<?= htmlspecialchars($item['image']) ?>"
                                alt="<?= htmlspecialchars($item['title']) ?>">

                            <h3>
                                <?= htmlspecialchars($item['title']) ?>
                            </h3>

                            <?php if (!empty($item['subtitle'])): ?>
                                <p>
                                    <?= htmlspecialchars($item['subtitle']) ?>
                                </p>
                            <?php endif; ?>

                            <?php if (!empty($item['price'])): ?>
                                <p class="price">
                                    <?= htmlspecialchars($item['price']) ?>
                                </p>
                            <?php endif; ?>

                            <a href="<?= htmlspecialchars($item['url']) ?>">
                                View more
                            </a>

                        </div>

                    <?php endforeach; ?>

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
