<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/css/main.css">
</head>

<body>
    <?php

    require_once '/var/www/shared/ui/carousel.php';

    $books = [

        [
            'title' => 'Jane Eyre',
            'subtitle' => 'Charlotte Brontë',
            'image' => 'images/jane-eyre.jpg',
            'url' => '/books/jane-eyre',
            'price' => '£8.99'
        ],

        [
            'title' => 'The Hobbit',
            'subtitle' => 'J.R.R. Tolkien',
            'image' => 'images/the-hobbit.jpg',
            'url' => '/books/the-hobbit',
            'price' => '£10.99'
        ],

        [
            'title' => 'Wuthering Heights',
            'subtitle' => 'Emily Brontë',
            'image' => 'images/wuthering-heights.jpg',
            'url' => '/books/wuthering-heights',
            'price' => '£7.99'
        ],

        [
            'title' => 'Dracula',
            'subtitle' => 'Bram Stoker',
            'image' => 'images/dracula.jpg',
            'url' => '/books/dracula',
            'price' => '£9.99'
        ],

        [
            'title' => 'Frankenstein',
            'subtitle' => 'Mary Shelley',
            'image' => 'images/frankenstein.jpg',
            'url' => '/books/frankenstein',
            'price' => '£8.99'
        ],

        [
            'title' => 'Alice in Wonderland',
            'subtitle' => 'Lewis Carroll',
            'image' => 'images/alice.jpg',
            'url' => '/books/alice',
            'price' => '£6.99'
        ],

        [
            'title' => 'The Secret Garden',
            'subtitle' => 'Frances Hodgson Burnett',
            'image' => 'images/secret-garden.jpg',
            'url' => '/books/secret-garden',
            'price' => '£7.99'
        ],

        [
            'title' => 'Treasure Island',
            'subtitle' => 'Robert Louis Stevenson',
            'image' => 'images/treasure-island.jpg',
            'url' => '/books/treasure-island',
            'price' => '£8.99'
        ],

        [
            'title' => 'The Picture of Dorian Gray',
            'subtitle' => 'Oscar Wilde',
            'image' => 'images/dorian-gray.jpg',
            'url' => '/books/dorian-gray',
            'price' => '£9.99'
        ],

        [
            'title' => 'Little Women',
            'subtitle' => 'Louisa May Alcott',
            'image' => 'images/little-women.jpg',
            'url' => '/books/little-women',
            'price' => '£8.99'
        ],

        [
            'title' => 'Great Expectations',
            'subtitle' => 'Charles Dickens',
            'image' => 'images/great-expectations.jpg',
            'url' => '/books/great-expectations',
            'price' => '£9.99'
        ],

        [
            'title' => 'The Time Machine',
            'subtitle' => 'H.G. Wells',
            'image' => 'images/time-machine.jpg',
            'url' => '/books/time-machine',
            'price' => '£7.99'
        ],

        [
            'title' => 'The Count of Monte Cristo',
            'subtitle' => 'Alexandre Dumas',
            'image' => 'images/monte-cristo.jpg',
            'url' => '/books/monte-cristo',
            'price' => '£12.99'
        ],

        [
            'title' => 'The Adventures of Sherlock Holmes',
            'subtitle' => 'Arthur Conan Doyle',
            'image' => 'images/sherlock-holmes.jpg',
            'url' => '/books/sherlock-holmes',
            'price' => '£9.99'
        ],

        [
            'title' => 'The Wind in the Willows',
            'subtitle' => 'Kenneth Grahame',
            'image' => 'images/wind-in-willows.jpg',
            'url' => '/books/wind-in-willows',
            'price' => '£7.99'
        ]

    ];


    $carousel = new Carousel(
        'Bestselling Books',
        $books
    );

    echo $carousel->render();

    ?>

    <script src="/js/carousel.js"></script>
</body>

</html>