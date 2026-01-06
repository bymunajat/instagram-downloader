<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Instagram Downloader</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    <!-- HEADER -->
    <header class="bg-white shadow-md">
        <div class="container mx-auto flex flex-col md:flex-row items-center justify-between py-6 px-4">
            <h1 id="title" class="text-2xl font-bold text-green-600 mb-2 md:mb-0">Instagram Downloader</h1>
            
            <nav class="flex gap-4 items-center text-gray-600 font-medium">
                <a href="#" class="nav-link hover:text-green-600 transition" data-key="home">Home</a>
                <a href="#" class="nav-link hover:text-green-600 transition" data-key="howtouse">How to Use</a>
                <a href="#" class="nav-link hover:text-green-600 transition" data-key="about">About</a>

                <!-- Download Dropdown -->
                <div class="relative">
                    <button id="downloadMenuBtn" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-500 transition focus:outline-none" data-key="download">Download ▼</button>
                    <div id="downloadDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-lg overflow-hidden z-50">
                        <a href="#video" class="block px-4 py-2 hover:bg-green-100 transition" data-key="video">Video</a>
                        <a href="#carousel" class="block px-4 py-2 hover:bg-green-100 transition" data-key="carousel">Carousel</a>
                        <a href="#igtv" class="block px-4 py-2 hover:bg-green-100 transition" data-key="igtv">IGTV</a>
                    </div>
                </div>

                <!-- Language Selector -->
                <select id="langSelector" class="ml-4 border border-gray-300 rounded-lg px-2 py-1 focus:ring-2 focus:ring-green-500">
                    <option value="en">English</option>
                    <option value="id">Indonesia</option>
                </select>
            </nav>
        </div>
    </header>

    <!-- MAIN -->
    <main class="container mx-auto flex-1 px-4 py-8">
        <div class="bg-white p-8 rounded-xl shadow-lg max-w-2xl mx-auto">
            <p id="subtitle" class="text-gray-600 text-center mb-6">Paste your Instagram Reel link and download the video instantly!</p>

            <form id="downloadForm" class="flex flex-col md:flex-row gap-4">
                <input type="text" id="instaUrl" placeholder="Paste Instagram link..." required
                       class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                <button type="submit" id="downloadBtn" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-500 transition">Download</button>
            </form>

            <div id="result" class="mt-8 flex flex-col gap-6">
                <!-- Video card akan muncul di sini -->
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="bg-white shadow-inner mt-8">
        <div class="container mx-auto py-4 text-center text-gray-500" id="footerText">&copy; 2026 Instagram Downloader</div>
    </footer>

    <!-- JS -->
    <script>
        const form = document.getElementById('downloadForm');
        const result = document.getElementById('result');
        const downloadMenuBtn = document.getElementById('downloadMenuBtn');
        const downloadDropdown = document.getElementById('downloadDropdown');
        const langSelector = document.getElementById('langSelector');

        // Bahasa data
        const i18n = {
            en: {
                title: "Instagram Downloader",
                home: "Home",
                howtouse: "How to Use",
                about: "About",
                download: "Download ▼",
                video: "Video",
                carousel: "Carousel",
                igtv: "IGTV",
                subtitle: "Paste your Instagram Reel link and download the video instantly!",
                downloadBtn: "Download",
                loading: "Loading...",
                error: "Error: Cannot fetch video",
                footer: "© 2026 Instagram Downloader"
            },
            id: {
                title: "Pengunduh Instagram",
                home: "Beranda",
                howtouse: "Cara Pakai",
                about: "Tentang",
                download: "Unduh ▼",
                video: "Video",
                carousel: "Carousel",
                igtv: "IGTV",
                subtitle: "Tempel link Instagram Reel dan unduh videonya sekarang!",
                downloadBtn: "Unduh",
                loading: "Memuat...",
                error: "Kesalahan: Tidak bisa mengambil video",
                footer: "© 2026 Pengunduh Instagram"
            }
        };

        function setLanguage(lang) {
            document.getElementById('title').textContent = i18n[lang].title;
            document.getElementById('subtitle').textContent = i18n[lang].subtitle;
            document.getElementById('downloadBtn').textContent = i18n[lang].downloadBtn;
            document.getElementById('downloadMenuBtn').textContent = i18n[lang].download;
            document.getElementById('footerText').textContent = i18n[lang].footer;

            // nav links
            document.querySelectorAll('.nav-link').forEach(el => {
                const key = el.getAttribute('data-key');
                if(i18n[lang][key]) el.textContent = i18n[lang][key];
            });

            // dropdown links
            downloadDropdown.querySelectorAll('a').forEach(el => {
                const key = el.getAttribute('data-key');
                if(i18n[lang][key]) el.textContent = i18n[lang][key];
            });
        }

        langSelector.addEventListener('change', (e) => {
            setLanguage(e.target.value);
        });

        // set default language
        setLanguage('en');

        // Form submit
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const url = document.getElementById('instaUrl').value.trim();
            if (!url) return;

            result.innerHTML = `<p class="text-center text-gray-500">${i18n[langSelector.value].loading}</p>`;

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
                            <a href="${videoUrl}" download="${filename}" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-500 transition">${i18n[langSelector.value].downloadBtn}</a>
                        </div>
                    `;
                } else {
                    result.innerHTML = `<p class="text-red-500 text-center font-semibold">${i18n[langSelector.value].error}</p>`;
                }
            } catch (err) {
                result.innerHTML = `<p class="text-red-500 text-center font-semibold">Error: ${err.message}</p>`;
            }
        });

        // Dropdown toggle
        downloadMenuBtn.addEventListener('click', () => {
            downloadDropdown.classList.toggle('hidden');
        });
        window.addEventListener('click', (e) => {
            if (!downloadMenuBtn.contains(e.target) && !downloadDropdown.contains(e.target)) {
                downloadDropdown.classList.add('hidden');
            }
        });
    </script>

</body>
</html>
