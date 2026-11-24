<?php

require_once '/var/www/shared/api.php';
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
} catch (RuntimeException $e) {
    echo $e->getMessage();
}
