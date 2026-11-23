<?php

require_once '/var/www/shared/ui/admin_sidebar.php';
require_once '/var/www/shared/router.php';
require_once '/var/www/shared/ajax.php';
require_once '/var/www/shared/api.php';
require_once '/var/www/shared/ui/carousel.php';
require_once '/var/www/shared/ui/table.php';
require_once '/var/www/shared/utils/text_utils.php';

$response = API::get('/genres');

$headers = ['ID', 'Name', 'Count'];
$rows = [];

foreach ($response->getData() as $item) {
    $data[] = [];

    $genreId = $item['genre_id'];

    $rows[] = [
        'data' => [
            'id' => $item['genre_id'],
            'name' => $item['name'],
            'book_count' => $item['book_count']
        ],
        'action' => ""
    ];
}

echo '<h2>Genres</h2>';
$table = new Table($headers, $rows);

$table->setClass('table table-striped table-bordered');

echo $table->render();
