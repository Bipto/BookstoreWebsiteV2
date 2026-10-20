<?php

require_once '/var/www/shared/api.php';

$id = $_GET['id'];

$response = API::delete("/cart?id={$id}");
echo $response->getData();
