<?php

require_once '/var/www/shared/api.php';

$id = $_GET['id'] ?? 1;

$response = API::get("/genres/{$id}");
$results = json_decode($response, true);

if ($results['success']) {
    $data = json_decode($results['data'], true);

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
