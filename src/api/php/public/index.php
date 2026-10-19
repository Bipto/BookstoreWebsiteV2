<?php

require_once '/var/www/shared/db.php';
require_once '/var/www/shared/router.php';

require_once '/var/www/api/private/url_validator.php';
require_once '/var/www/api/private/controllers/auth_controller.php';
require_once '/var/www/api/private/controllers/book_controller.php';
require_once '/var/www/api/private/controllers/cart_controller.php';
require_once '/var/www/api/private/controllers/genre_controller.php';
require_once '/var/www/api/private/controllers/user_controller.php';

//handle authorization of requesting URL
$allowedOrigins = [
    "",
    'http://localhost',
    'http://admin.localhost',
];

if (!UrlValidator::validate($allowedOrigins)) {
    exit;
}

function openDB(): RelationalDatabase
{
    return new RelationalDatabase(
        $_ENV['POSTGRES_DRIVER'],
        $_ENV['POSTGRES_HOST'],
        $_ENV['POSTGRES_PORT'],
        $_ENV['POSTGRES_DB'],
        $_ENV['POSTGRES_USER'],
        $_ENV['POSTGRES_PASSWORD'],
        $_ENV['POSTGRES_CHARSET']
    );
}

$db = openDB();
$router = new Router();
$authController = new AuthController($db);
$bookController = new BookController($db);
$cartController = new CartController($db);
$genreController = new GenreController($db);
$userController = new UserController($db);

$router->get(
    '/books',
    [$bookController, 'getBooks']
);
$router->get(
    '/books/{id}',
    fn($id) => $bookController->getBookById($id)
);
$router->get(
    '/genres',
    [$genreController, 'getGenres']
);
$router->get(
    '/genres/{id}',
    fn($id) => $genreController->getGenreById($id)
);
$router->post(
    '/users',
    [$userController, 'addUser']
);
$router->post(
    '/cart',
    [$cartController, 'addBook']
);
$router->get(
    '/cart',
    [$cartController, 'getCart']
);
$router->delete(
    '/cart',
    [$cartController, 'removeItem']
);
$router->post(
    '/auth/login',
    [$authController, 'handleLogin']
);
$router->dispatch();
