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
    $imagePath = sanitiseImageUrl(getAuthorImagePath($data['image_path']));
    ?>

    <form id="edit-author-form" method="POST">

        <div class="edit-modal-form">

            <img src="<?= $imagePath ?>" class="view-book-image" loading="lazy">

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

<?php endif; ?>