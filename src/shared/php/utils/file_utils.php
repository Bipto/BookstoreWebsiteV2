<?php

function getImagePath(string $filename): string
{
    return "http://assets.localhost/img/{$filename}";
}

function getBookImagePath(string $filename): string
{
    return "http://assets.localhost/img/books/{$filename}";
}

function getAuthorImagePath(string $filename): string
{
    return "http://assets.localhost/img/authors/{$filename}";
}

function sanitiseImageUrl(string $url): string
{
    return htmlspecialchars($url);
}

function uploadImage(string $folder, array $file): ?string
{
    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {

        $errors = [
            UPLOAD_ERR_INI_SIZE   => 'File exceeds upload_max_filesize in php.ini.',
            UPLOAD_ERR_FORM_SIZE  => 'File exceeds the form MAX_FILE_SIZE limit.',
            UPLOAD_ERR_PARTIAL    => 'File was only partially uploaded.',
            UPLOAD_ERR_NO_FILE    => 'No file was uploaded.',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary upload directory.',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
            UPLOAD_ERR_EXTENSION  => 'A PHP extension stopped the upload.'
        ];

        $error = $errors[$file['error']] ?? 'Unknown upload error.';

        throw new RuntimeException(
            "Image upload failed (code {$file['error']}): {$error}"
        );
    }

    // Validate MIME type
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);

    $allowedTypes = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp'
    ];

    if (!isset($allowedTypes[$mimeType])) {
        throw new RuntimeException('Invalid image type.');
    }

    // Validate image contents
    if (getimagesize($file['tmp_name']) === false) {
        throw new RuntimeException('Invalid image file.');
    }

    // Maximum file size: 5MB
    if ($file['size'] > 5 * 1024 * 1024) {
        throw new RuntimeException('Image must be smaller than 5MB.');
    }

    $extension = $allowedTypes[$mimeType];

    // Generate a unique filename
    $filename = bin2hex(random_bytes(16)) . '.' . $extension;

    // Server directory
    $uploadDirectory = '/var/www/shared/img/';

    if (!is_dir($uploadDirectory)) {
        mkdir($uploadDirectory, 0755, true);
    }

    $destination = $uploadDirectory . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new RuntimeException('Unable to save uploaded image.');
    }

    // Return the relative path to store in your database
    return "img/{$folder}{$filename}";
}
