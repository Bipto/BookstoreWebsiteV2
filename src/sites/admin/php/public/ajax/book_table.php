<?php

require_once '/var/www/shared/ui/admin_sidebar.php';
require_once '/var/www/shared/router.php';
require_once '/var/www/shared/ajax.php';
require_once '/var/www/shared/api.php';
require_once '/var/www/shared/ui/carousel.php';
require_once '/var/www/shared/ui/table.php';
require_once '/var/www/shared/utils/text_utils.php';

$response = API::get('/books');

$headers = ['ID', 'Title', 'Author', 'Price'];
$rows = [];

foreach ($response->getData() as $item) {
    $data[] = [];

    $bookId = $item['book_id'];

    $rows[] = [
        'data' => [
            'id' => $item['book_id'],
            'title' => $item['title'],
            'author' => $item['author'],
            'price' => $item['price']
        ],
        'action' => "openBookModal({$bookId});"
    ];
}

echo '<h2>Books</h2>';
$table = new Table($headers, $rows);

$table->setClass('table table-striped table-bordered');

echo $table->render();
