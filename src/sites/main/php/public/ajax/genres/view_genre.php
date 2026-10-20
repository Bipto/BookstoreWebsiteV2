<?php

require_once '/var/www/shared/api.php';

$id = $_GET['id'] ?? 1;

$response = API::get("/genres/{$id}");

if (!$response->hasError()) {
    $data = $response->getData();

    $name = $data['name'] ?? '';
    $imagePath = isset($data['image_path']) ? "/img/{$data['image_path']}" : '';
    $description = $data['description'] ?? '';

    $html = "
        <div class='view-book'>
            <h1>{$name}</h1>
            <img src='{$imagePath}' class='view-book-image' loading='lazy'>
            <p>{$description}</p>
        </div>";

    echo $html;
} else {
    echo '<h3>Could not find book!</h3>';
}
