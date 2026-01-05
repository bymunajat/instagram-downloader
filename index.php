<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Instagram Video Downloader</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    <!-- HEADER -->
    <header class="bg-white shadow-md">
        <div class="container mx-auto flex flex-col md:flex-row items-center justify-between py-6 px-4">
            <h1 class="text-2xl font-bold text-green-600 mb-2 md:mb-0">Instagram Downloader</h1>
            
            <nav class="flex gap-6 items-center text-gray-600 font-medium">
                <a href="#" class="hover:text-green-600 transition">Home</a>
                <a href="#" class="hover:text-green-600 transition">How to Use</a>
                <a href="#" class="hover:text-green-600 transition">About</a>

                <!-- DROPDOWN MENU -->
                <div class="relative">
                    <button id="downloadMenuBtn" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-500 transition focus:outline-none">
                        Download ▼
                    </button>
                    <div id="downloadDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-lg overflow-hidden z-50">
                        <a href="#video" class="block px-4 py-2 hover:bg-green-100 transition">Video</a>
                        <a href="#carousel" class="block px-4 py-2 hover:bg-green-100 transition">Carousel</a>
                        <a href="#igtv" class="block px-4 py-2 hover:bg-green-100 transition">IGTV</a>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <!-- MAIN CONTAINER -->
    <main class="container mx-auto flex-1 px-4 py-8">
        <div class="bg-white p-8 rounded-xl shadow-lg max-w-2xl mx-auto">
            <p class="text-gray-600 text-center mb-6">Paste your Instagram Reel link and download the video instantly!</p>

            <!-- FORM -->
            <form id="downloadForm" class="flex flex-col md:flex-row gap-4">
                <input type="text" id="instaUrl" placeholder="Paste Instagram link..." required
                       class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-500 transition">Download</button>
            </form>

            <!-- RESULT -->
            <div id="result" class="mt-8 flex flex-col gap-6">
                <!-- Video card akan muncul di sini -->
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="bg-white shadow-inner mt-8">
        <div class="container mx-auto py-4 text-center text-gray-500">
            &copy; 2026 Instagram Video Downloader
        </div>
    </footer>

    <!-- JS -->
    <script>
        const form = document.getElementById('downloadForm');
        const result = document.getElementById('result');

        // Handle form submit
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const url = document.getElementById('instaUrl').value.trim();
            if (!url) return;

            result.innerHTML = `<p class="text-center text-gray-500">Loading...</p>`;

            try {
                const res = await fetch('download.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ url })
                });

                const data = await res.json();

                if (data.status === 'redirect') {
                    const videoUrl = data.url;
                    const filename = data.filename || 'instagram_video.mp4';

                    result.innerHTML = `
                        <div class="bg-gray-50 p-6 rounded-xl shadow-md flex flex-col items-center gap-4">
                            <video controls class="rounded-lg max-w-full">
                                <source src="${videoUrl}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                            <a href="${videoUrl}" download="${filename}" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-500 transition">Download Video</a>
                        </div>
                    `;
                } else {
                    result.innerHTML = `<p class="text-red-500 text-center font-semibold">Error: ${data.error?.code || 'Cannot fetch video'}</p>`;
                }
            } catch (err) {
                result.innerHTML = `<p class="text-red-500 text-center font-semibold">Error: ${err.message}</p>`;
            }
        });

        // Handle dropdown toggle
        const downloadMenuBtn = document.getElementById('downloadMenuBtn');
        const downloadDropdown = document.getElementById('downloadDropdown');

        downloadMenuBtn.addEventListener('click', () => {
            downloadDropdown.classList.toggle('hidden');
        });

        // Klik di luar dropdown untuk menutup
        window.addEventListener('click', (e) => {
            if (!downloadMenuBtn.contains(e.target) && !downloadDropdown.contains(e.target)) {
                downloadDropdown.classList.add('hidden');
            }
        });
    </script>

</body>
</html>
