<?php

session_start();

?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Site</title>
    <link rel="stylesheet" href="/css/admin.css">

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/htmx.org@2.0.4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script defer src="/js/modal.js"></script>
    <script defer src="/js/edit_author_modal.js"></script>
</head>

<body>

    <?php

    require_once '/var/www/shared/ui/admin_sidebar.php';
    require_once '/var/www/shared/router.php';
    require_once '/var/www/shared/ajax.php';
    require_once '/var/www/shared/api.php';
    require_once '/var/www/shared/ui/carousel.php';
    require_once '/var/www/shared/ui/table.php';
    require_once '/var/www/shared/ui/modal.php';

    $sidebar = new BookstoreSidebar();
    $sidebar->render();

    echo AJAX::call('/ajax/book_table.php', 'page-content');

    $sidebar->close();

    echo ModalBuilder::build('author-modal');
    ?>

</body>

</html>