<?php

session_start();

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Selby Bookstore</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/main.css">

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/htmx.org@2.0.4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="/js/cart.js"></script>
</head>

<body>
    <?php

    require_once '/var/www/shared/menubar.php';
    require_once '/var/www/shared/router.php';
    require_once '/var/www/shared/ajax.php';
    require_once '/var/www/shared/api.php';

    $links = [
        [
            'Home' => '/',
            'Books' => '/books',
            'Categories' => '/categories',
            'About' => '/about',
            'Contact' => '/contact'
        ]
    ];

    $email = $_SESSION['email'] ?? '';
    $email = urlencode($email);

    $cartCount = 0;

    $url = "/cart?email={$email}";
    $response = API::get($url);
    if (!$response->hasError()) {
        $data = $response->getData();
        $cartCount = count($data['cart']);
    }

    $menubar = new Menubar($links, $cartCount);
    echo $menubar->build();

    $router = new Router();
    $router->get('/', '/var/www/sites/main/private/pages/main.php');
    $router->get('/books', '/var/www/sites/main/private/pages/books.php');
    $router->get('/categories', '/var/www/sites/main/private/pages/categories.php');
    $router->get('/about', '/var/www/sites/main/private/pages/about.php');
    $router->get('/contact', '/var/www/sites/main/private/pages/contact.php');

    $router->get('/view_book/{book_id}', function ($id) {
        echo AJAX::call("/ajax/books/view_book.php?id={$id}", 'page-content');
    });

    $router->get('/view_genre/{genre_id}', function ($id) {
        echo AJAX::call("/ajax/genres/view_genre.php?id={$id}", 'page-content');
    });

    $router->get('/login', '/var/www/sites/main/private/pages/login.php');
    $router->get('/create_account', '/var/www/sites/main/private/pages/create_account.php');
    $router->get('/logout', '/var/www/sites/main/private/utils/handle_logout.php');
    $router->get('/checkout', '/var/www/sites/main/private/pages/checkout.php');

    $router->post('/login', '/var/www/sites/main/private/utils/handle_login.php');
    $router->post('/create_account', '/var/www/sites/main/private/utils/handle_registration.php');

    $router->dispatch();

    ?>
</body>

</html>