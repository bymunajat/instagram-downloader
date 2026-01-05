<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Instagram Video Downloader</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Instagram Video Downloader</h1>
        <p class="subtitle">Paste your Instagram Reel link and get the video instantly!</p>

        <form id="downloadForm">
            <input type="text" id="instaUrl" placeholder="Paste Instagram link here..." required>
            <button type="submit">Submit</button>
        </form>

        <div id="result" class="result">
            <!-- Hasil video akan muncul di sini -->
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>
