<?php

require_once '/var/www/shared/api.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$email = $_POST['email'] ?? null;
$firstName = $_POST['first-name'] ?? null;
$lastName = $_POST['last-name'] ?? null;
$password = $_POST['password'] ?? null;

$data = [
    'email' => $email,
    'password' => $password
];

if (!is_null($email) && !is_null($firstName) && !is_null($lastName) && !is_null($password)) {

    $data = [
        'email' => $email,
        'firstName' => $firstName,
        'lastName' => $lastName,
        'password' => $password
    ];

    $response = API::post('/users', $data);
    if (!$response->hasError()) {
        $_SESSION['email'] = $email;
        echo '<script>window.location.href="/"</script>';
        exit;
    } else {
        echo '<script>alert("Account creation failed");</script>';
        echo '<script>window.location.href="/create_account"</script>';
        exit;
    }
}
