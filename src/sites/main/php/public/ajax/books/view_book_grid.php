<?php

require_once '/var/www/shared/api.php';
require_once '/var/www/shared/ui/book_card.php';
require_once '/var/www/shared/utils/file_utils.php';

$queryString = $_SERVER['QUERY_STRING'];
$url = $queryString != '' ? "/books?{$queryString}" : "/books";

$response = API::get($url);

$bookCards = [];

if (!$response->hasError()) {
    foreach ($response->getData() as $book) {
        $bookCards[] = new BookCard(
            $book['title'],
            $book['author'],
            getImagePath($book['image_path']),
            "/view_book/{$book['book_id']}",
            $book['price']
        );
    }
}

?>

<?php if (!$response->hasError()): ?>

    <div class="book-grid">

        <?php

        foreach ($bookCards as $bookCard) {
            echo $bookCard->render();
        }

        ?>

    </div>
<?php else: ?>
    <h3>Failed to connect to database and retrieve books</h3>
<?php endif; ?>