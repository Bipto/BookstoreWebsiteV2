<?php

require_once '/var/www/shared/api.php';
require_once '/var/www/shared/ajax.php';
require_once '/var/www/shared/ui/modal.php';

$response = API::get('/genres?active=true');
$data = $response->getData();
$genreCount = count($data);

?>

<div class="book-catalogue">

    <aside class="book-filters">

        <button
            type="button"
            class="book-filters-toggle"
            aria-expanded="false"
            aria-controls="book-filter-content">

            <span>Filters</span>

            <span class="filter-arrow" aria-hidden="true"></span>
        </button>

        <div class="book-filter-content" id="book-filter-content">

            <label for="book-search">Search books</label>

            <input
                type="search"
                id="book-search"
                placeholder="Search by title..."
                autocomplete="off">

            <label for="author-search">Search authors</label>

            <input
                type="search"
                id="author-search"
                placeholder="Search by author..."
                autocomplete="off">

            <?php if ($genreCount > 0): ?>

                <hr>
                <fieldset>
                    <legend>Genres</legend>

                    <?php foreach ($data as $genre): ?>

                        <label>
                            <input
                                type="checkbox"
                                name="genre"
                                value="<?= htmlspecialchars($genre['genre_id']) ?>">

                            <?= htmlspecialchars($genre['name']) ?>
                            (<?= htmlspecialchars($genre['book_count']) ?>)
                        </label>

                    <?php endforeach; ?>

                </fieldset>

            <?php endif; ?>

        </div>
    </aside>

    <?= AJAX::call('/ajax/books/view_book_grid.php', 'page-content'); ?>

</div>

<?= ModalBuilder::build('book-modal'); ?>

<script type="module" src="/js/view_book_modal.js"></script>