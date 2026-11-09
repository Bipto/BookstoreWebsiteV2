<?php

$queryString = $_SERVER['QUERY_STRING'];
$parameters = [];
parse_str($queryString, $parameters);

$id = $parameters['id'] ?? 0;

require_once '/var/www/shared/api.php';

$response = API::get("/authors/{$id}");

for ($i = 0; $i < 100_000_000_000; $i++) {
    $i = $i * $i;
}

?>

<?php if ($response->hasError()): ?>
    <p><?= $response->getErrorMessage(); ?></p>
<?php else: ?>
    <?php $data = $response->getData(); ?>

    <label for="name">Name:</label>
    <input type="text" name="name" value="<?= $data['name'] ?>"> </input>

    <label for="description">Description:</label>
    <textarea rows="15" name="description" style="width: 100%; resize: none;"> <?= $data['description'] ?></textarea>
<?php endif; ?>