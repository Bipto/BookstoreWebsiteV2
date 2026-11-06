<?php

require_once '/var/www/shared/ui/admin_sidebar.php';
require_once '/var/www/shared/router.php';
require_once '/var/www/shared/ajax.php';
require_once '/var/www/shared/api.php';
require_once '/var/www/shared/ui/carousel.php';
require_once '/var/www/shared/ui/table.php';
require_once '/var/www/shared/utils/text_utils.php';

$response = API::get('/authors');

/* $headers = ['ID', 'Name', 'Email', 'Age'];

$data = [
    [1, 'John Smith', 'john@example.com', 32],
    [2, 'Jane Jones', 'jane@example.com', 28],
    [3, 'Peter Brown', 'peter@example.com', 45]
]; */

$headers = ['ID', 'Name', 'Description', 'Image Path'];
$data = [];

foreach ($response->getData() as $item) {
    $data[] = [
        'id' => $item['author_id'],
        'name' => $item['name'],
        'description' => truncate($item['description'], 150),
        'image_path' => $item['image_path']
    ];
}

echo '<h2>Users</h2>';
$table = new Table($headers, $data);

$table->setClass('table table-striped table-bordered');

echo $table->render();
