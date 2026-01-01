<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Services\CobaltService;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['url'])) {
    $url = $_POST['url'];
    $service = new CobaltService();
    $result = $service->downloadInstagram($url);

    echo '<h3>Result:</h3><pre>';
    print_r($result);
    echo '</pre>';
}
?>

<form method="post">
    <input type="text" name="url" placeholder="Paste Instagram URL" style="width:400px;">
    <button type="submit">Download</button>
</form>
