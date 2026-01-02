<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Services\CobaltService;

$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['url'])) {
    $url = $_POST['url'];
    $service = new CobaltService();
    $result = $service->downloadInstagram($url);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Instagram Downloader</title>
</head>
<body style="margin:0;">

<form method="post" style="
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    height:100vh;
    gap:12px;
">
    <input
        type="text"
        name="url"
        placeholder="Paste Instagram URL"
        style="
            width:400px;
            padding:10px;
            font-size:16px;
        "
        required
    >
    <button
        type="submit"
        style="
            padding:10px 20px;
            font-size:16px;
            cursor:pointer;
        "
    >
        Download
    </button>

    <?php if ($result): ?>
        <div style="
            margin-top:20px;
            width:80%;
            max-width:800px;
            text-align:left;
        ">
            <h3>Result:</h3>
            <pre style="
                background:#f5f5f5;
                padding:15px;
                overflow:auto;
            "><?php print_r($result); ?></pre>
        </div>
    <?php endif; ?>
</form>

</body>
</html>
