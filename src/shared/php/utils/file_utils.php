<?php

function getAssetUrl(string $filename): string
{
    return "http://assets.localhost/{$filename}";
}

function sanitiseAssetUrl(string $url): string
{
    return htmlspecialchars($url);
}


function uploadImage(string $folder, array $file): ?string
{
    // No file uploaded
    if (
        !isset($file['error']) ||
        $file['error'] === UPLOAD_ERR_NO_FILE
    ) {
        return null;
    }

    // Check upload errors
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
            "Image upload failed: {$error}"
        );
    }

    // Validate required upload fields
    if (
        empty($file['tmp_name']) ||
        !is_uploaded_file($file['tmp_name'])
    ) {
        throw new RuntimeException('Invalid uploaded file.');
    }

    // Maximum file size: 5MB
    $maxSize = 5 * 1024 * 1024;

    if ($file['size'] > $maxSize) {
        throw new RuntimeException('Image must be smaller than 5MB.');
    }

    // Validate MIME type using file contents
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);

    $allowedTypes = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp'
    ];

    if (!isset($allowedTypes[$mimeType])) {
        throw new RuntimeException('Invalid image type.');
    }

    // Validate actual image contents
    $imageInfo = getimagesize($file['tmp_name']);

    if ($imageInfo === false) {
        throw new RuntimeException('Invalid image file.');
    }

    // Ensure MIME type matches image contents
    if ($imageInfo['mime'] !== $mimeType) {
        throw new RuntimeException('Image MIME type mismatch.');
    }

    // Sanitise folder name
    $folder = trim($folder, '/\\');

    if (
        $folder !== '' &&
        !preg_match('/^[a-zA-Z0-9_-]+$/', $folder)
    ) {
        throw new RuntimeException('Invalid upload folder.');
    }

    // Base upload directory
    $baseDirectory = '/var/www/sites/assets/public/img';

    // Build destination directory
    $uploadDirectory = $baseDirectory;

    if ($folder !== '') {
        $uploadDirectory .= DIRECTORY_SEPARATOR . $folder;
    }

    // Create directory if necessary
    if (
        !is_dir($uploadDirectory) &&
        !mkdir($uploadDirectory, 0755, true) &&
        !is_dir($uploadDirectory)
    ) {
        throw new RuntimeException('Unable to create upload directory.');
    }

    // Generate unique filename
    $extension = $allowedTypes[$mimeType];
    $filename = bin2hex(random_bytes(16)) . '.' . $extension;

    $destination = $uploadDirectory . DIRECTORY_SEPARATOR . $filename;

    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new RuntimeException('Unable to save uploaded image.');
    }

    //Return relative path
    return $folder !== ''
        ? "img/{$folder}/{$filename}"
        : "img/{$filename}";
}
