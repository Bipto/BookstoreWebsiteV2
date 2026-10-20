<?php

require_once '/var/www/shared/api.php';

session_start();

$email = $_SESSION['email'];
$bookId = $_GET['book_id'] ?? null;

$response = API::post('/cart', [
    'email' => $email,
    'bookId' => $bookId
]);

echo json_encode($response->getData());
