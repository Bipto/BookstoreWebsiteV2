<?php

require_once '/var/www/shared/ui/carousel.php';
require_once '/var/www/shared/api.php';

$queryString = $_SERVER['QUERY_STRING'];
$url = "/books?limit=15";

$response = API::get($url);

$parsedBooks = [];
foreach ($response->getData() as $book) {
    $parsedBooks[] = [
        'title' => $book['title'],
        'subtitle' => $book['author'],
        'image' => "/img/{$book['image_path']}",
        'url' => '#',
        'price' => $book['price']
    ];
}

$bestSellersCarousel = new Carousel(
    'Bestselling Books',
    $parsedBooks,
    'carousel'
);

echo $bestSellersCarousel->render();

$bestSellersCarousel2 = new Carousel(
    'Bestselling Books 2',
    $parsedBooks,
    'carousel2'
);

echo $bestSellersCarousel2->render();
