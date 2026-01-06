<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Instagram Downloader</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">

<!-- ================= HEADER ================= -->
<header class="bg-white shadow-md">
    <div class="container mx-auto flex flex-col md:flex-row items-center justify-between py-6 px-4">
        <h1 class="text-2xl font-bold text-green-600">
            Instagram Downloader
        </h1>

        <nav class="flex gap-4 items-center text-gray-600 font-medium mt-3 md:mt-0">
            <a href="#" class="nav-link hover:text-green-600" data-page="home">Home</a>
            <a href="#" class="nav-link hover:text-green-600" data-page="how">How to Use</a>
            <a href="#" class="nav-link hover:text-green-600" data-page="about">About</a>

            <select id="langSelector"
                class="ml-4 border border-gray-300 rounded-lg px-2 py-1">
                <option value="en">English</option>
                <option value="id">Indonesia</option>
            </select>
        </nav>
    </div>
</header>

<!-- ================= MAIN ================= -->
<main class="container mx-auto flex-1 px-4 py-8">
    <div class="bg-white p-8 rounded-xl shadow-lg max-w-2xl mx-auto">

        <!-- HOME -->
        <section id="page-home" class="page">
            <p id="subtitle" class="text-gray-600 text-center mb-6">
                Paste your Instagram link and download media instantly.
            </p>

            <form id="downloadForm" class="flex flex-col md:flex-row gap-4">
                <input type="text" id="instaUrl"
                    placeholder="Paste Instagram link..."
                    required
                    class="flex-1 border border-gray-300 rounded-lg px-4 py-2">

                <button type="submit" id="downloadBtn"
                    class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-500">
                    Download
                </button>
            </form>

            <div id="result" class="mt-8"></div>
        </section>

        <!-- HOW -->
        <section id="page-how" class="page hidden">
            <h2 class="text-xl font-bold mb-4">How to Use</h2>
            <ol class="list-decimal list-inside space-y-2 text-gray-600">
                <li>Copy Instagram post / reel / carousel link</li>
                <li>Paste link into input</li>
                <li>Click Download</li>
                <li>Choose media and download</li>
            </ol>
        </section>

        <!-- ABOUT -->
        <section id="page-about" class="page hidden">
            <h2 class="text-xl font-bold mb-4">About</h2>
            <p class="text-gray-600 mb-2">
                Simple Instagram Downloader powered by Cobalt API.
            </p>
            <p class="text-sm text-gray-500">
                Only public content is supported.
            </p>
        </section>

    </div>
</main>

<!-- ================= FOOTER ================= -->
<footer class="bg-white shadow-inner mt-8">
    <div id="footerText"
        class="container mx-auto py-4 text-center text-gray-500">
        © 2026 Instagram Downloader
    </div>
</footer>

<!-- ================= SCRIPT ================= -->
<script>
/* PAGE SWITCH */
document.querySelectorAll('.nav-link').forEach(link => {
    link.onclick = e => {
        e.preventDefault();
        document.querySelectorAll('.page').forEach(p => p.classList.add('hidden'));
        document.getElementById('page-' + link.dataset.page).classList.remove('hidden');
    };
});

/* LANGUAGE */
const i18n = {
    en: {
        subtitle: "Paste your Instagram link and download media instantly.",
        download: "Download",
        loading: "Loading...",
        error: "Failed to fetch media"
    },
    id: {
        subtitle: "Tempel link Instagram dan unduh media.",
        download: "Unduh",
        loading: "Memuat...",
        error: "Gagal mengambil media"
    }
};

const langSelector = document.getElementById('langSelector');
function setLang(lang) {
    subtitle.textContent = i18n[lang].subtitle;
    downloadBtn.textContent = i18n[lang].download;
}
setLang('en');
langSelector.onchange = e => setLang(e.target.value);

/* DOWNLOAD */
const form = document.getElementById('downloadForm');
const result = document.getElementById('result');

form.onsubmit = async e => {
    e.preventDefault();
    const url = instaUrl.value.trim();
    if (!url) return;

    result.innerHTML = `<p class="text-center text-gray-500">${i18n[langSelector.value].loading}</p>`;

    const res = await fetch('download.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ url })
    });

    const data = await res.json();

    if (data.status === 'single') {
        renderSingle(data);
        return;
    }

    if (data.status === 'multiple') {
        renderMultiple(data.media);
        return;
    }

    result.innerHTML = `<p class="text-red-500 text-center">${i18n[langSelector.value].error}</p>`;
};

/* RENDER SINGLE */
function renderSingle(data) {
    result.innerHTML = `
        <div class="bg-gray-50 p-6 rounded-xl shadow flex flex-col gap-4 items-center">
            ${data.type === 'video'
                ? `<video controls class="rounded-lg max-w-full"><source src="${data.url}"></video>`
                : `<img src="${data.url}" class="rounded-lg max-w-full">`
            }

            <a href="download.php?action=download&url=${encodeURIComponent(data.url)}"
               class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-500">
               ${i18n[langSelector.value].download}
            </a>
        </div>
    `;
}

/* RENDER MULTIPLE */
function renderMultiple(media) {
    let html = `<div class="grid grid-cols-1 md:grid-cols-2 gap-4">`;

    media.forEach((item, i) => {
        html += `
        <div class="bg-gray-50 p-4 rounded-xl shadow flex flex-col gap-3">
            ${item.type === 'video'
                ? `<video controls class="rounded-lg"><source src="${item.url}"></video>`
                : `<img src="${item.url}" class="rounded-lg">`
            }

            <a href="download.php?action=download&url=${encodeURIComponent(item.url)}"
               class="bg-green-600 text-white px-4 py-2 rounded-lg text-center hover:bg-green-500">
               Download ${item.type} ${i + 1}
            </a>
        </div>`;
    });

    html += `</div>`;
    result.innerHTML = html;
}
</script>

</body>
</html>
