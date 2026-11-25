<?php

$queryString = $_SERVER['QUERY_STRING'];
$parameters = [];
parse_str($queryString, $parameters);

$id = $parameters['id'] ?? 0;

require_once '/var/www/shared/api.php';
require_once '/var/www/shared/utils/file_utils.php';

$response = API::get("/authors/{$id}");

?>

<?php if ($response->hasError()): ?>
    <p><?= $response->getErrorMessage(); ?></p>
<?php else: ?>
    <?php
    $data = $response->getData();
    $imagePath = sanitiseAssetUrl(getAssetUrl($data['image_path']));
    ?>

    <form
        id="edit-author-form"
        method="POST"
        enctype="multipart/form-data">

        <div class="edit-modal-form">

            <img
                id="author-image-preview"
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

            <label for="name">Name:</label>
            <input
                type="text"
                id="name"
                name="name"
                value="<?= htmlspecialchars($data['name']) ?>">

            <label for="description">Description:</label>
            <textarea
                id="description"
                name="description"
                rows="15"
                style="width: 100%; resize: none;"><?= htmlspecialchars($data['description']) ?></textarea>

        </div>

    </form>

    <script>
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('author-image-preview');

        imageInput.addEventListener('change', function() {

            const file = this.files[0];

            if (file) {
                imagePreview.src = URL.createObjectURL(file);
            }

        });
    </script>
<?php endif; ?>