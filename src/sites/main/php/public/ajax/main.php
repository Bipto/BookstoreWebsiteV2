<?php

require_once '/var/www/shared/ui/author_card.php';
require_once '/var/www/shared/ui/book_card.php';
require_once '/var/www/shared/ui/carousel.php';
require_once '/var/www/shared/api.php';
require_once '/var/www/shared/utils/file_utils.php';

//best-sellers
$books = API::get("/books?limit=15");

$bookCards = [];
foreach ($books->getData() as $book) {
    $bookCards[] = new BookCard(
        $book['title'],
        $book['author'],
        getBookImagePath($book['image_path']),
        "/book/{$book['book_id']}",
        $book['price']
    );
}

$bestSellersCarousel = new Carousel(
    'Bestselling Books',
    $bookCards,
    'bestSellersCarousel'
);

echo $bestSellersCarousel->render();

//authors
$authors = API::get('/authors?limit=15');
$authorCards = [];
foreach ($authors->getData() as $author) {
    $authorCards[] = new AuthorCard(
        $author['name'],
        getAuthorImagePath($author['image_path']),
        "/author/{$author['author_id']}",
    );
}

$authorCarousel = new Carousel(
    'Bestselling Authors',
    $authorCards,
    'authorsCarousel'
);

echo $authorCarousel->render();

//genres
$genres = API::get('/genres?limit=15&active=true');
$genreCards = [];
foreach ($genres->getData() as $genre) {
    $genreId = "{$genre['genre_id']}";

    $genreBook = API::get("/books?genre={$genreId}&limit=1");
    $bookData = $genreBook->getData() ?? [];

    if (!$genreBook->hasError() && count($bookData) === 1) {
        $genreCards[] = new AuthorCard(
            $genre['name'],
            getBookImagePath($bookData[0]['image_path']),
            "/genre/{$genreId}",
        );
    }
}

$genreCarousel = new Carousel(
    'Bestselling Genres',
    $genreCards,
    'genresCarousel'
);

echo $genreCarousel->render();
?>

<script>
    registerCarouselCallbacks();
    document.currentScript.remove();
</script>