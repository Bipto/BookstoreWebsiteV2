<?php

require_once '/var/www/shared/rest_builder.php';
require_once '/var/www/shared/api.php';
require_once '/var/www/shared/utils/file_utils.php';
require_once '/var/www/shared/utils/file_utils.php';

try {

    $data = $_POST;

    if (
        isset($_FILES['image']) &&
        $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE
    ) {
        $imagePath = uploadImage('authors', $_FILES['image']);
        if ($imagePath !== null) {
            $data['image_path'] = $imagePath;
        }
    }

    API::put("/authors", $data);
    REST::response(200, []);
} catch (RuntimeException $e) {
    REST::response(401, ['error' => $e->getMessage()]);
}
