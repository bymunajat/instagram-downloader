const form = document.getElementById('downloadForm');
const result = document.getElementById('result');

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const url = document.getElementById('instaUrl').value.trim();
    if (!url) return;

    result.innerHTML = `<p>Loading...</p>`;

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
                <video controls class="video-preview">
                    <source src="${videoUrl}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <button id="downloadBtn">Download Video</button>
            `;

            document.getElementById('downloadBtn').addEventListener('click', () => {
                const a = document.createElement('a');
                a.href = videoUrl;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                a.remove();
            });
        } else {
            result.innerHTML = `<p style="color:red;">Error: ${data.error?.code || 'Cannot fetch video'}</p>`;
        }
    } catch (err) {
        result.innerHTML = `<p style="color:red;">Error: ${err.message}</p>`;
    }
});
