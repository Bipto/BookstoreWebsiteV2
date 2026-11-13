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

    <div class="edit-modal-form">
        <img src='<?= $imagePath ?>' class='view-book-image' loading='lazy'>

        <label for="name">Name:</label>
        <input type="text" name="name" value="<?= $data['name'] ?>"> </input>

        <label for="description">Description:</label>
        <textarea rows="15" name="description" style="width: 100%; resize: none;"> <?= $data['description'] ?></textarea>
    </div>
<?php endif; ?>