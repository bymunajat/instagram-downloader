<?php
/**
 * Admin Panel - PHP Native
 * Instagram Downloader Management System
 */

require_once 'config.php';
require_once 'includes/functions.php';

// Handle login
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin.php');
        exit;
    } else {
        $error = 'Invalid credentials! Use: ' . ADMIN_USERNAME . ' / ' . ADMIN_PASSWORD;
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}

// Check if logged in
$isLoggedIn = isAdminLoggedIn();

// Load settings for display
$websiteSettings = getWebsiteSettings();
$seoSettings = getSEOSettings();
$siteName = $websiteSettings['siteName'] ?? APP_NAME;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($siteName); ?> - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .sidebar-active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
    </style>
</head>
<body class="bg-gray-100">

<?php if (!$isLoggedIn): ?>
<!-- Login Page -->
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-purple-600 via-pink-500 to-purple-700">
    <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Admin Panel</h1>
            <p class="text-gray-600"><?php echo htmlspecialchars($siteName); ?> Management System</p>
        </div>
        
        <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <input type="hidden" name="login" value="1">
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Username</label>
                <input type="text" name="username" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-purple-500 outline-none" placeholder="Enter username" required autofocus>
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Password</label>
                <input type="password" name="password" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-purple-500 outline-none" placeholder="Enter password" required>
            </div>
            
            <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-pink-500 text-white py-3 rounded-lg font-bold hover:shadow-lg transition">
                Login
            </button>
            
            <p class="text-sm text-gray-500 mt-4 text-center">Default: <?php echo ADMIN_USERNAME; ?> / <?php echo ADMIN_PASSWORD; ?></p>
        </form>
    </div>
</div>
<?php else: ?>
<!-- Admin Dashboard -->
<!-- Header -->
<header class="bg-white shadow-md">
    <div class="flex items-center justify-between px-6 py-4">
        <h1 class="text-2xl font-bold text-gray-800"><?php echo htmlspecialchars($siteName); ?> Admin</h1>
        <a href="?logout=1" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600" onclick="return confirm('Are you sure you want to logout?')">
            Logout
        </a>
    </div>
</header>

<div class="flex">
<!-- Sidebar -->
<aside class="w-64 bg-white h-screen shadow-lg overflow-y-auto">
    <nav class="p-4 space-y-1">
        <a href="#" class="sidebar-link sidebar-active block px-4 py-3 rounded-lg" data-section="dashboard">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v18h16.5M8.25 15.75v-6m4.5 6V6.75m4.5 9v-3"/>
                </svg>
                <span>Dashboard</span>
            </div>
        </a>
        <a href="#" class="sidebar-link block px-4 py-3 rounded-lg hover:bg-gray-100" data-section="settings">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.094c.55 0 1.02.398 1.11.94l.149.894a1.125 1.125 0 001.068.916h.942c.62 0 1.07.606.868 1.19l-.31.91a1.125 1.125 0 00.326 1.207l.687.56c.459.375.459 1.082 0 1.457l-.687.56a1.125 1.125 0 00-.326 1.207l.31.91c.203.584-.248 1.19-.868 1.19h-.942a1.125 1.125 0 00-1.068.916l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.02-.398-1.11-.94l-.149-.894a1.125 1.125 0 00-1.068-.916h-.942c-.62 0-1.07-.606-.868-1.19l.31-.91a1.125 1.125 0 00-.326-1.207l-.687-.56c-.459-.375-.459-1.082 0-1.457l.687-.56a1.125 1.125 0 00.326-1.207l-.31-.91c-.203-.584.248-1.19.868-1.19h.942a1.125 1.125 0 001.068-.916l.149-.894z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>Website Settings</span>
            </div>
        </a>
        <a href="#" class="sidebar-link block px-4 py-3 rounded-lg hover:bg-gray-100" data-section="seo">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.85-4.4a7.5 7.5 0 11-15 0 7.5 7.5 0 0115 0z"/>
                </svg>
                <span>SEO Settings</span>
            </div>
        </a>
        <a href="#" class="sidebar-link block px-4 py-3 rounded-lg hover:bg-gray-100" data-section="pages">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 6h-15A1.5 1.5 0 003 7.5v9A1.5 1.5 0 004.5 18h15A1.5 1.5 0 0021 16.5v-9A1.5 1.5 0 0019.5 6z"/>
                </svg>
                <span>Page Management</span>
            </div>
        </a>
    </nav>
</aside>

<!-- Main Content -->
<main class="flex-1 p-8 overflow-y-auto h-screen">
    
    <!-- Dashboard Section -->
    <section id="section-dashboard" class="content-section">
        <h2 class="text-3xl font-bold mb-6">Dashboard</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-xl shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Pages</p>
                        <h3 class="text-3xl font-bold text-gray-800">7</h3>
                    </div>
                    <div class="text-4xl">📄</div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Languages</p>
                        <h3 class="text-3xl font-bold text-gray-800">2</h3>
                    </div>
                    <div class="text-4xl">🌐</div>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md">
            <h3 class="text-xl font-bold mb-4">Quick Actions</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <button onclick="showSection('settings')" class="bg-gradient-to-r from-purple-500 to-pink-500 text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg">
                    Website Settings
                </button>
                <button onclick="showSection('seo')" class="bg-gradient-to-r from-blue-500 to-cyan-500 text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg">
                    SEO Settings
                </button>
            </div>
        </div>
    </section>

    <!-- Website Settings Section -->
    <section id="section-settings" class="content-section hidden">
        <h2 class="text-3xl font-bold mb-6">Website Settings</h2>
        
        <div class="bg-white p-6 rounded-xl shadow-md mb-6">
            <h3 class="text-xl font-bold mb-4">Branding</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Website Name</label>
                    <input type="text" id="siteName" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg" value="<?php echo htmlspecialchars($websiteSettings['siteName'] ?? APP_NAME); ?>" placeholder="MySeoFan">
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Website Tagline</label>
                    <input type="text" id="siteTagline" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg" value="<?php echo htmlspecialchars($websiteSettings['siteTagline'] ?? ''); ?>" placeholder="Download Instagram Content">
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md mb-6">
            <h3 class="text-xl font-bold mb-4">Header Settings</h3>
            <label class="block text-gray-700 font-semibold mb-2">Custom Header Code (HTML/CSS/JS)</label>
            <textarea id="headerCode" rows="5" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg font-mono text-sm" placeholder="<!-- Add custom code here -->"><?php echo htmlspecialchars($websiteSettings['headerCode'] ?? ''); ?></textarea>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md mb-6">
            <h3 class="text-xl font-bold mb-4">Footer Settings</h3>
            <label class="block text-gray-700 font-semibold mb-2">Custom Footer Code (HTML/CSS/JS)</label>
            <textarea id="footerCode" rows="5" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg font-mono text-sm" placeholder="<!-- Add custom code here -->"><?php echo htmlspecialchars($websiteSettings['footerCode'] ?? ''); ?></textarea>
        </div>

        <button onclick="saveSettings()" class="bg-gradient-to-r from-purple-600 to-pink-500 text-white px-8 py-3 rounded-lg font-bold hover:shadow-lg">
            Save Settings
        </button>
    </section>

    <!-- SEO Settings Section -->
    <section id="section-seo" class="content-section hidden">
        <h2 class="text-3xl font-bold mb-6">SEO Settings</h2>
        
        <div class="bg-white p-6 rounded-xl shadow-md mb-6">
            <h3 class="text-xl font-bold mb-4">Homepage SEO</h3>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Meta Title</label>
                <input type="text" id="homeMetaTitle" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg" value="<?php echo htmlspecialchars($seoSettings['homeMetaTitle'] ?? ''); ?>" placeholder="MySeoFan - Download Instagram Content">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Meta Description</label>
                <textarea id="homeMetaDesc" rows="3" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg" placeholder="Download Instagram videos, photos, reels & IGTV instantly..."><?php echo htmlspecialchars($seoSettings['homeMetaDesc'] ?? ''); ?></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Meta Keywords</label>
                <input type="text" id="homeMetaKeywords" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg" value="<?php echo htmlspecialchars($seoSettings['homeMetaKeywords'] ?? ''); ?>" placeholder="instagram downloader, download ig video">
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md mb-6">
            <h3 class="text-xl font-bold mb-4">Open Graph Tags (OG Tags)</h3>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">OG Title</label>
                <input type="text" id="ogTitle" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg" value="<?php echo htmlspecialchars($seoSettings['ogTitle'] ?? ''); ?>" placeholder="MySeoFan - Instagram Downloader">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">OG Description</label>
                <textarea id="ogDesc" rows="2" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg"><?php echo htmlspecialchars($seoSettings['ogDesc'] ?? ''); ?></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">OG Image URL</label>
                <input type="text" id="ogImage" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg" value="<?php echo htmlspecialchars($seoSettings['ogImage'] ?? ''); ?>" placeholder="https://myseofan.com/og-image.jpg">
            </div>
        </div>

        <button onclick="saveSEO()" class="bg-gradient-to-r from-blue-600 to-cyan-500 text-white px-8 py-3 rounded-lg font-bold hover:shadow-lg">
            Save SEO Settings
        </button>
    </section>

    <!-- Page Management Section -->
    <section id="section-pages" class="content-section hidden">
        <h2 class="text-3xl font-bold mb-6">Page Management</h2>
        <div class="bg-white p-6 rounded-xl shadow-md mb-6">
            <h3 class="text-xl font-bold mb-4">Default Pages (7)</h3>
            <p class="text-gray-600">Page management feature coming soon...</p>
        </div>
    </section>

</main>
</div>

<script>
// Sidebar navigation
document.querySelectorAll('.sidebar-link').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const section = this.dataset.section;
        showSection(section);
    });
});

function showSection(section) {
    document.querySelectorAll('.content-section').forEach(s => s.classList.add('hidden'));
    document.getElementById('section-' + section).classList.remove('hidden');
    document.querySelectorAll('.sidebar-link').forEach(link => {
        link.classList.remove('sidebar-active');
        if (link.dataset.section === section) {
            link.classList.add('sidebar-active');
        }
    });
}

// Show Toast Notification
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-2xl z-50 transform transition-all duration-300 ${type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'}`;
    toast.innerHTML = `<div class="flex items-center gap-3"><span>${message}</span><button onclick="this.parentElement.parentElement.remove()" class="ml-4 font-bold">×</button></div>`;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100px)';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Save Settings
function saveSettings() {
    const formData = new FormData();
    formData.append('action', 'save_settings');
    formData.append('siteName', document.getElementById('siteName').value);
    formData.append('siteTagline', document.getElementById('siteTagline').value);
    formData.append('headerCode', document.getElementById('headerCode').value);
    formData.append('footerCode', document.getElementById('footerCode').value);

    fetch('admin-api.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
        } else {
            showToast(data.error || 'Failed to save settings', 'error');
        }
    })
    .catch(err => {
        showToast('Error: ' + err.message, 'error');
    });
}

// Save SEO
function saveSEO() {
    const formData = new FormData();
    formData.append('action', 'save_seo');
    formData.append('homeMetaTitle', document.getElementById('homeMetaTitle').value);
    formData.append('homeMetaDesc', document.getElementById('homeMetaDesc').value);
    formData.append('homeMetaKeywords', document.getElementById('homeMetaKeywords').value);
    formData.append('ogTitle', document.getElementById('ogTitle').value);
    formData.append('ogDesc', document.getElementById('ogDesc').value);
    formData.append('ogImage', document.getElementById('ogImage').value);

    fetch('admin-api.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
        } else {
            showToast(data.error || 'Failed to save SEO settings', 'error');
        }
    })
    .catch(err => {
        showToast('Error: ' + err.message, 'error');
    });
}
</script>

</body>
</html>
<?php endif; ?>
