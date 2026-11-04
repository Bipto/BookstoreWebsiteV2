<?php

require_once '/var/www/shared/ui/book_card.php';
require_once '/var/www/shared/ui/carousel.php';
require_once '/var/www/shared/api.php';

//best-sellers
$response = API::get("/books?limit=15");

$bookCards = [];
foreach ($response->getData() as $book) {
    $bookCards[] = new BookCard(
        $book['title'],
        $book['author'],
        "img/{$book['image_path']}",
        "/view_book/{$book['book_id']}",
        $book['price']
    );
}

$bestSellersCarousel = new Carousel(
    'Bestselling Books',
    $bookCards,
    'bestSellersCarousel'
);

echo $bestSellersCarousel->render();

$authors = API::get('/authors?limit=15');

//authors
$bestSellersCarousel2 = new Carousel(
    'Authors',
    $bookCards,
    'authorsCarousel'
);

echo $bestSellersCarousel2->render();
