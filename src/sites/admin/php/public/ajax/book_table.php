<?php

require_once '/var/www/shared/ui/admin_sidebar.php';
require_once '/var/www/shared/router.php';
require_once '/var/www/shared/ajax.php';
require_once '/var/www/shared/api.php';
require_once '/var/www/shared/ui/carousel.php';
require_once '/var/www/shared/ui/table.php';
require_once '/var/www/shared/utils/text_utils.php';

$response = API::get('/authors');

$headers = ['ID', 'Name', 'Description', 'Image Path'];
$rows = [];

foreach ($response->getData() as $item) {
    $data[] = [];

    $authorId = $item['author_id'];

    $rows[] = [
        'data' => [
            'id' => $item['author_id'],
            'name' => $item['name'],
            'description' => truncate($item['description'], 150),
            'image_path' => $item['image_path']
        ],
        'action' => "openAuthorModal({$authorId});"
    ];
}

echo '<h2>Users</h2>';
$table = new Table($headers, $rows);

$table->setClass('table table-striped table-bordered');

echo $table->render();
