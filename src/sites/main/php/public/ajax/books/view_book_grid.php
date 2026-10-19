<?php

require_once '/var/www/shared/api.php';

$queryString = $_SERVER['QUERY_STRING'];
$url = $queryString != '' ? "/books?{$queryString}" : "/books";

$response = API::get($url);
$results = json_decode($response, true);
$decodedData = json_decode($results['data'], true) ?? [];

?>

<?php if ($results['success']): ?>

    <div class="book-grid">

        <?php foreach ($decodedData as $result): ?>

            <?php
            $bookId = htmlspecialchars(
                $result['book_id'],
                ENT_QUOTES,
                'UTF-8'
            );

            $title = htmlspecialchars(
                $result['title'],
                ENT_QUOTES,
                'UTF-8'
            );

            $imagePath = htmlspecialchars(
                "/img/{$result['image_path']}",
                ENT_QUOTES,
                'UTF-8'
            );

            ?>

            <button
                class="book-link"
                type="button"
                data-book-id="<?= htmlspecialchars($result['book_id'], ENT_QUOTES, 'UTF-8') ?>">
                <div class="book">
                    <img
                        src="<?= htmlspecialchars("/img/{$result['image_path']}", ENT_QUOTES, 'UTF-8') ?>"
                        class="book-image"
                        loading="lazy"
                        alt="<?= htmlspecialchars($result['title'], ENT_QUOTES, 'UTF-8') ?>">

                    <h3 class="book-title">
                        <?= htmlspecialchars($result['title'], ENT_QUOTES, 'UTF-8') ?>
                    </h3>

                    <span class="tooltiptext">
                        <?= htmlspecialchars($result['title'], ENT_QUOTES, 'UTF-8') ?>
                    </span>
                </div>
            </button>


        <?php endforeach; ?>
    </div>
<?php else: ?>
    <h1>Failed to connect to database and retrieve books</h1>
<?php endif; ?>