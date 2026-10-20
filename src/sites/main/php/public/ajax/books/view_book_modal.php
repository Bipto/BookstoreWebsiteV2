<?php

require_once '/var/www/shared/api.php';
require_once '/var/www/shared/utils/text_utils.php';

$id = $_GET['id'] ?? 1;

$response = API::get("/books/{$id}");

$output = [
    'success' => false
];

if (!$response->hasError()) {
    $data = $response->getData();

    $imagePath = "/img/" . rawurlencode($data['image_path']);
    $price = (float) ($data['price'] ?? 0);
    $formattedPrice = number_format($price, 2);

    $description = $data['book_description'] ?? '';
    $displayDescription = truncate($description, 450);

    $genres = json_decode($data['genres'] ?? '[]', true) ?: [];

    $genreHTML = '';

    foreach ($genres as $index => $genre) {
        $genreHTML .= sprintf(
            "<a class='genre-link' href='/view_genre/{$genre['genre_id']}'>%s</a>",
            htmlspecialchars($genre['name'], ENT_QUOTES, 'UTF-8')
        );
    }

    $title = htmlspecialchars($data['title'] ?? '', ENT_QUOTES, 'UTF-8');
    $author = htmlspecialchars($data['author'] ?? '', ENT_QUOTES, 'UTF-8');
    $description = $displayDescription ?? '';

    $header = "
    <h2 id='book-modal-title'>
        {$title}
        <span class='book-modal-author'>by {$author}</span>
    </h2>
";

    $content = "
    <div class='view-book'>

        <div class='view-book-genres'>
            {$genreHTML}
        </div>

        <img
            src='{$imagePath}'
            class='view-book-image'
            alt='Cover of {$title}'
            loading='lazy'
        >

        <div class='view-book-details'>
            <div class='view-book-price'>£{$formattedPrice}</div>

            <p>{$description}</p>
        </div>

    </div>
";

    $encodedId = urlencode($id);

    $footer = "
    <a
        href='/view_book/{$encodedId}'
        class='modal-button modal-button-secondary'
    >
        View More
    </a>

    <button
        type='button'
        class='modal-button add-to-cart'
        onclick='AddToCart({$id})'
    >
        Add to cart
    </button>
";

    $output['header'] = $header;
    $output['content'] = $content;
    $output['footer'] = $footer;
} else {
    $output['error'] = '<h3>Could not find book!</h3>';
}

echo json_encode($output);
