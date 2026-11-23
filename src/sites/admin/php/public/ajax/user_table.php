<?php

require_once '/var/www/shared/ui/admin_sidebar.php';
require_once '/var/www/shared/router.php';
require_once '/var/www/shared/ajax.php';
require_once '/var/www/shared/api.php';
require_once '/var/www/shared/ui/carousel.php';
require_once '/var/www/shared/ui/table.php';
require_once '/var/www/shared/utils/text_utils.php';

$response = API::get('/users');

$headers = ['ID', 'Email', 'First Name', 'Last Name'];
$rows = [];

foreach ($response->getData() as $item) {
    $data[] = [];

    $userId = $item['user_id'];

    $rows[] = [
        'data' => [
            'id' => $item['user_id'],
            'email' => $item['email'],
            'first_name' => $item['first_name'],
            'last_name' => $item['last_name']
        ],
        'action' => "console.log('{$userId}');"
    ];
}

echo '<h2>Users</h2>';
$table = new Table($headers, $rows);

$table->setClass('table table-striped table-bordered');

echo $table->render();
