/**
 * Main JavaScript for Instagram Downloader
 */

// Paste button
pasteBtn.addEventListener('click', async () => {
    try {
        const text = await navigator.clipboard.readText();
        instaUrl.value = text;
    } catch (err) {
        alert('Cannot access clipboard. Please paste manually.');
    }
});

// Download form
downloadForm.addEventListener('submit', async e => {
    e.preventDefault();
    const url = instaUrl.value.trim();
    if (!url) {
        alert('Please enter an Instagram link first!');
        return;
    }

    // Show loading
    result.innerHTML = `
        <div class="card-white rounded-2xl p-8 animate-bounce-in">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                    <svg class="w-7 h-7 text-white animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800 text-lg">Processing...</h4>
                    <p class="text-sm text-gray-600">Downloading media from Instagram</p>
                </div>
            </div>
            <div class="bg-gray-100 rounded-lg p-4">
                <p class="text-sm text-gray-700 break-all">${url}</p>
            </div>
        </div>
    `;
    
    // Scroll to result
    setTimeout(() => {
        result.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }, 100);

    try {
        const res = await fetch('download.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ url })
        });

        const data = await res.json();
        
        if (data.status === 'single') {
            renderSingleMedia(data);
        } else if (data.status === 'multiple') {
            renderCarouselMedia(data.media);
        } else {
            result.innerHTML = `
                <div class="card-white rounded-2xl p-8 animate-bounce-in">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-800 text-xl mb-2">Failed to Download</h4>
                        <p class="text-gray-600">Make sure the Instagram URL is correct and the account is public.</p>
                    </div>
                </div>
            `;
        }
    } catch (err) {
        result.innerHTML = `
            <div class="card-white rounded-2xl p-8 animate-bounce-in">
                <div class="text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <h4 class="font-bold text-gray-800 text-xl mb-2">Error</h4>
                    <p class="text-gray-600">${err.message}</p>
                </div>
            </div>
        `;
    }
    
    instaUrl.value = '';
});

// Render Single Media (Video/Photo)
function renderSingleMedia(data) {
    const downloadUrl = `download.php?action=download&url=${encodeURIComponent(data.url)}`;
    
    let mediaHtml = '';
    if (data.type === 'video') {
        mediaHtml = `
            <video controls class="w-full rounded-2xl shadow-lg max-w-4xl">
                <source src="${downloadUrl}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        `;
    } else {
        mediaHtml = `
            <img src="${downloadUrl}" alt="Instagram Media" class="w-full rounded-2xl shadow-lg max-w-4xl object-contain">
        `;
    }
    
    result.innerHTML = `
        <div class="card-white rounded-2xl p-8 animate-bounce-in">
            <div class="flex flex-col items-center gap-6">
                ${mediaHtml}
                <a href="${downloadUrl}" 
                   class="bg-gradient-to-r from-blue-500 to-cyan-400 text-white px-10 py-4 rounded-xl font-bold hover:shadow-2xl transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Download ${data.type === 'video' ? 'Video' : 'Photo'}
                </a>
            </div>
        </div>
    `;
}

// Render Carousel Media (Multiple)
function renderCarouselMedia(mediaList) {
    const mediaItems = mediaList.map((item, index) => {
        const downloadUrl = `download.php?action=download&url=${encodeURIComponent(item.url)}`;
        
        let mediaHtml = '';
        if (item.type === 'video') {
            mediaHtml = `
                <video controls class="w-full rounded-xl shadow-md">
                    <source src="${downloadUrl}" type="video/mp4">
                </video>
            `;
        } else {
            mediaHtml = `
                <img src="${downloadUrl}" alt="Instagram Media ${index + 1}" class="w-full rounded-xl shadow-md object-contain">
            `;
        }
        
        return `
            <div class="card-white rounded-2xl p-6 animate-bounce-in" style="animation-delay: ${index * 0.1}s">
                <div class="flex flex-col gap-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-semibold text-gray-500">Slide ${index + 1}</span>
                        <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-semibold uppercase">${item.type}</span>
                    </div>
                    ${mediaHtml}
                    <a href="${downloadUrl}" 
                       class="bg-gradient-to-r from-purple-500 to-pink-500 text-white px-6 py-3 rounded-xl font-bold text-center hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download ${item.type === 'video' ? 'Video' : 'Photo'}
                    </a>
                </div>
            </div>
        `;
    }).join('');
    
    result.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            ${mediaItems}
        </div>
    `;
}

// FAQ smooth scroll
document.querySelector('a[href="#faq"]')?.addEventListener('click', e => {
    e.preventDefault();
    document.getElementById('faq').scrollIntoView({ behavior: 'smooth' });
});

// Multi-language Support
const i18n = {
    en: {
        heroTitle: 'Download Instagram Content',
        heroSubtitle: 'Save any Instagram video, photo, reel or IGTV instantly',
        btnVideo: 'Download Video',
        btnPhoto: 'Download Photo',
        btnReels: 'Download Reels',
        btnIGTV: 'Download IGTV',
        inputPlaceholder: 'Paste your Instagram link here...',
        btnPaste: 'Paste',
        btnDownload: 'Download',
        faq: 'FAQ'
    },
    id: {
        heroTitle: 'Unduh Konten Instagram',
        heroSubtitle: 'Simpan video, foto, reel atau IGTV Instagram secara instan',
        btnVideo: 'Unduh Video',
        btnPhoto: 'Unduh Foto',
        btnReels: 'Unduh Reels',
        btnIGTV: 'Unduh IGTV',
        inputPlaceholder: 'Tempel link Instagram Anda di sini...',
        btnPaste: 'Tempel',
        btnDownload: 'Unduh',
        faq: 'Tanya Jawab'
    }
};

// Language selector
document.getElementById('langSelector')?.addEventListener('change', e => {
    const lang = e.target.value;
    fetch('?lang=' + lang, { method: 'GET' })
        .then(() => location.reload())
        .catch(() => {
            // Fallback to client-side translation
            const t = i18n[lang];
            if (document.getElementById('heroTitle')) {
                document.getElementById('heroTitle').innerHTML = `${t.heroTitle}<br><span class="bg-gradient-to-r from-pink-300 to-purple-300 bg-clip-text text-transparent">In Seconds - 100% Free</span>`;
            }
            if (document.getElementById('heroSubtitle')) {
                document.getElementById('heroSubtitle').innerText = t.heroSubtitle;
            }
            if (document.getElementById('btnVideo')) {
                document.getElementById('btnVideo').innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>${t.btnVideo}`;
            }
            if (document.getElementById('btnPhoto')) {
                document.getElementById('btnPhoto').innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>${t.btnPhoto}`;
            }
            if (document.getElementById('btnReels')) {
                document.getElementById('btnReels').innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/></svg>${t.btnReels}`;
            }
            if (document.getElementById('btnIGTV')) {
                document.getElementById('btnIGTV').innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>${t.btnIGTV}`;
            }
            if (document.getElementById('instaUrl')) {
                document.getElementById('instaUrl').placeholder = t.inputPlaceholder;
            }
            if (document.getElementById('btnPasteText')) {
                document.getElementById('btnPasteText').innerText = t.btnPaste;
            }
            if (document.getElementById('btnDownloadText')) {
                document.getElementById('btnDownloadText').innerText = t.btnDownload;
            }
            if (document.querySelector('a[href="#faq"]')) {
                document.querySelector('a[href="#faq"]').innerText = t.faq;
            }
        });
});
