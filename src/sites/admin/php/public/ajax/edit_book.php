<?php

require_once '/var/www/shared/api.php';
require_once '/var/www/shared/utils/file_utils.php';

$queryString = $_SERVER['QUERY_STRING'];
$parameters = [];
parse_str($queryString, $parameters);

$id = $parameters['id'] ?? 0;

$response = API::get("/books/{$id}");

?>

<?php if ($response->hasError()): ?>
    <p><?= $response->getErrorMessage(); ?></p>
<?php else: ?>
    <?php
    $data = $response->getData();
    $imagePath = getAssetUrl($data['image_path']);
    ?>

    <form
        id="edit-book-form"
        method="POST"
        enctype="multipart/form-data">

        <div class="edit-modal-form">

            <img
                id="book-image-preview"
                src="<?= htmlspecialchars($imagePath) ?>"
                class="view-book-image"
                loading="lazy">

            <label for="image">Change image:</label>
            <input
                type="file"
                id="image"
                name="image"
                accept="image/jpeg,image/png,image/webp">

            <input type="hidden" name="author_id" value="<?= $id ?>">

            <label for="title">Title:</label>
            <input
                type="text"
                id="title"
                name="title"
                value="<?= htmlspecialchars($data['title']) ?>">

            <label for="description">Description:</label>
            <textarea
                id="description"
                name="description"
                rows="15"
                style="width: 100%; resize: none;"><?= htmlspecialchars($data['book_description']) ?></textarea>

        </div>

    </form>
<?php endif; ?>