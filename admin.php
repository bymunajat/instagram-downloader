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
        $error = 'Invalid credentials!';
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js"></script>
    <style>
        .sidebar-active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 50;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: #fff;
            margin: auto;
            padding: 0;
            border-radius: 12px;
            max-width: 800px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }
    </style>
</head>

<body class="bg-gray-100">

    <?php if (!$isLoggedIn): ?>
        <!-- Login Page -->
        <div
            class="min-h-screen flex items-center justify-center bg-gradient-to-br from-purple-600 via-pink-500 to-purple-700">
            <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Admin Panel</h1>
                    <p class="text-gray-600"><?php echo htmlspecialchars($siteName); ?></p>
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
                        <input type="text" name="username"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-purple-500 outline-none"
                            required autofocus>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-2">Password</label>
                        <input type="password" name="password"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-purple-500 outline-none"
                            required>
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-purple-600 to-pink-500 text-white py-3 rounded-lg font-bold hover:shadow-lg transition">
                        Login
                    </button>
                    <p class="text-sm text-gray-500 mt-4 text-center">Default: <?php echo ADMIN_USERNAME; ?> /
                        <?php echo ADMIN_PASSWORD; ?>
                    </p>
                </form>
            </div>
        </div>

    <?php else: ?>
        <!-- Admin Dashboard -->
        <header class="bg-white shadow-md sticky top-0 z-40">
            <div class="flex items-center justify-between px-6 py-4">
                <h1 class="text-2xl font-bold text-gray-800"><?php echo htmlspecialchars($siteName); ?> Admin</h1>
                <a href="?logout=1" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600"
                    onclick="return confirm('Logout?')">
                    Logout
                </a>
            </div>
        </header>

        <div class="flex">
            <!-- Sidebar -->
            <aside class="w-64 bg-white h-screen shadow-lg overflow-y-auto sticky top-16">
                <nav class="p-4 space-y-1">
                    <a href="#" class="sidebar-link sidebar-active block px-4 py-3 rounded-lg" data-section="dashboard">
                        <div class="flex items-center gap-3">
                            <span>📊 Dashboard</span>
                        </div>
                    </a>

                    <a href="#" class="sidebar-link block px-4 py-3 rounded-lg hover:bg-gray-100" data-section="settings">
                        <div class="flex items-center gap-3">
                            <span>⚙️ Website Settings</span>
                        </div>
                    </a>

                    <a href="#" class="sidebar-link block px-4 py-3 rounded-lg hover:bg-gray-100" data-section="seo">
                        <div class="flex items-center gap-3">
                            <span>🔍 SEO Settings</span>
                        </div>
                    </a>

                    <a href="#" class="sidebar-link block px-4 py-3 rounded-lg hover:bg-gray-100" data-section="pages">
                        <div class="flex items-center gap-3">
                            <span>📄 Pages</span>
                        </div>
                    </a>

                    <a href="#" class="sidebar-link block px-4 py-3 rounded-lg hover:bg-gray-100" data-section="blog">
                        <div class="flex items-center gap-3">
                            <span>📝 Blog Posts</span>
                        </div>
                    </a>

                    <a href="#" class="sidebar-link block px-4 py-3 rounded-lg hover:bg-gray-100" data-section="redirects">
                        <div class="flex items-center gap-3">
                            <span>🔗 Redirects</span>
                        </div>
                    </a>

                    <a href="#" class="sidebar-link block px-4 py-3 rounded-lg hover:bg-gray-100"
                        data-section="translations">
                        <div class="flex items-center gap-3">
                            <span>🌍 Translations</span>
                        </div>
                    </a>
                </nav>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 p-8 overflow-y-auto" style="height: calc(100vh - 64px);">

                <!-- Dashboard Section -->
                <section id="section-dashboard" class="content-section">
                    <h2 class="text-3xl font-bold mb-6">Dashboard</h2>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        <div class="bg-white p-6 rounded-xl shadow-md">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500 text-sm">Pages</p>
                                    <h3 class="text-3xl font-bold text-gray-800" id="stat-pages">0</h3>
                                </div>
                                <div class="text-4xl">📄</div>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-xl shadow-md">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500 text-sm">Blog Posts</p>
                                    <h3 class="text-3xl font-bold text-gray-800" id="stat-blog">0</h3>
                                </div>
                                <div class="text-4xl">📝</div>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-xl shadow-md">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500 text-sm">Redirects</p>
                                    <h3 class="text-3xl font-bold text-gray-800" id="stat-redirects">0</h3>
                                </div>
                                <div class="text-4xl">🔗</div>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-xl shadow-md">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500 text-sm">Languages</p>
                                    <h3 class="text-3xl font-bold text-gray-800">7</h3>
                                </div>
                                <div class="text-4xl">🌐</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-md">
                        <h3 class="text-xl font-bold mb-4">Quick Actions</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <button onclick="showSection('blog')"
                                class="bg-gradient-to-r from-purple-500 to-pink-500 text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg">
                                New Blog Post
                            </button>
                            <button onclick="showSection('pages')"
                                class="bg-gradient-to-r from-green-500 to-emerald-500 text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg">
                                New Page
                            </button>
                            <button onclick="showSection('seo')"
                                class="bg-gradient-to-r from-blue-500 to-cyan-500 text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg">
                                SEO Settings
                            </button>
                            <button onclick="showSection('settings')"
                                class="bg-gradient-to-r from-orange-500 to-red-500 text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg">
                                Site Settings
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
                                <input type="text" id="siteName"
                                    class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg"
                                    value="<?php echo htmlspecialchars($websiteSettings['siteName'] ?? ''); ?>">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Tagline</label>
                                <input type="text" id="siteTagline"
                                    class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg"
                                    value="<?php echo htmlspecialchars($websiteSettings['siteTagline'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-md mb-6">
                        <h3 class="text-xl font-bold mb-4">Logo & Favicon</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Logo Upload</label>
                                <input type="file" id="logoUpload"
                                    class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg" accept="image/*">
                                <p class="text-sm text-gray-500 mt-2">Recommended: 200x50px PNG</p>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Favicon Upload</label>
                                <input type="file" id="faviconUpload"
                                    class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg" accept="image/*">
                                <p class="text-sm text-gray-500 mt-2">Recommended: 32x32px ICO/PNG</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-md mb-6">
                        <h3 class="text-xl font-bold mb-4">Header Settings</h3>
                        <label class="block text-gray-700 font-semibold mb-2">Custom Header Code (HTML/CSS/JS)</label>
                        <textarea id="headerCode" rows="5"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg font-mono text-sm"><?php echo htmlspecialchars($websiteSettings['headerCode'] ?? ''); ?></textarea>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-md mb-6">
                        <h3 class="text-xl font-bold mb-4">Footer Settings</h3>
                        <label class="block text-gray-700 font-semibold mb-2">Custom Footer Code (HTML/CSS/JS)</label>
                        <textarea id="footerCode" rows="5"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg font-mono text-sm"><?php echo htmlspecialchars($websiteSettings['footerCode'] ?? ''); ?></textarea>
                    </div>

                    <button onclick="saveSettings()"
                        class="bg-gradient-to-r from-purple-600 to-pink-500 text-white px-8 py-3 rounded-lg font-bold hover:shadow-lg">
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
                            <input type="text" id="homeMetaTitle"
                                class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg"
                                value="<?php echo htmlspecialchars($seoSettings['homeMetaTitle'] ?? ''); ?>">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Meta Description</label>
                            <textarea id="homeMetaDesc" rows="3"
                                class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg"><?php echo htmlspecialchars($seoSettings['homeMetaDesc'] ?? ''); ?></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Meta Keywords</label>
                            <input type="text" id="homeMetaKeywords"
                                class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg"
                                value="<?php echo htmlspecialchars($seoSettings['homeMetaKeywords'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-md mb-6">
                        <h3 class="text-xl font-bold mb-4">Open Graph Tags</h3>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">OG Title</label>
                            <input type="text" id="ogTitle" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg"
                                value="<?php echo htmlspecialchars($seoSettings['ogTitle'] ?? ''); ?>">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">OG Description</label>
                            <textarea id="ogDesc" rows="2"
                                class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg"><?php echo htmlspecialchars($seoSettings['ogDesc'] ?? ''); ?></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">OG Image URL</label>
                            <input type="text" id="ogImage" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg"
                                value="<?php echo htmlspecialchars($seoSettings['ogImage'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-md mb-6">
                        <h3 class="text-xl font-bold mb-4">Schema Markup</h3>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Organization Name</label>
                            <input type="text" id="schemaOrgName"
                                class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg"
                                value="<?php echo htmlspecialchars($seoSettings['schemaOrgName'] ?? ''); ?>">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Website Type</label>
                            <select id="schemaType" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg">
                                <option value="WebApplication" <?php echo ($seoSettings['schemaType'] ?? '') === 'WebApplication' ? 'selected' : ''; ?>>Web Application</option>
                                <option value="WebSite" <?php echo ($seoSettings['schemaType'] ?? '') === 'WebSite' ? 'selected' : ''; ?>>Website</option>
                                <option value="SoftwareApplication" <?php echo ($seoSettings['schemaType'] ?? '') === 'SoftwareApplication' ? 'selected' : ''; ?>>Software Application</option>
                            </select>
                        </div>
                    </div>

                    <button onclick="saveSEO()"
                        class="bg-gradient-to-r from-blue-600 to-cyan-500 text-white px-8 py-3 rounded-lg font-bold hover:shadow-lg">
                        Save SEO Settings
                    </button>
                </section>

                <!-- Pages Section -->
                <section id="section-pages" class="content-section hidden">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-3xl font-bold">Pages</h2>
                        <button onclick="openPageModal()"
                            class="bg-gradient-to-r from-green-500 to-emerald-500 text-white px-6 py-2 rounded-lg font-semibold">
                            + New Page
                        </button>
                    </div>

                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">URL Slug
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="pagesList" class="divide-y">
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-gray-500">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- Blog Section -->
                <section id="section-blog" class="content-section hidden">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-3xl font-bold">Blog Posts</h2>
                        <button onclick="openBlogModal()"
                            class="bg-gradient-to-r from-blue-500 to-cyan-500 text-white px-6 py-2 rounded-lg font-semibold">
                            + New Post
                        </button>
                    </div>

                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slug</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="blogPostsList" class="divide-y">
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- Redirects Section -->
                <section id="section-redirects" class="content-section hidden">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-3xl font-bold">Redirects</h2>
                        <button onclick="openRedirectModal()"
                            class="bg-gradient-to-r from-orange-500 to-red-500 text-white px-6 py-2 rounded-lg font-semibold">
                            + Add Redirect
                        </button>
                    </div>

                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">From URL
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">To URL</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Type</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="redirectsList" class="divide-y">
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- Translations Section -->
                <section id="section-translations" class="content-section hidden">
                    <h2 class="text-3xl font-bold mb-6">Translation Manager</h2>
                    <p class="mb-6 text-gray-600">Click on a language card to translate website content.</p>
                    <div id="languagesList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Loaded via JS -->
                        <div class="col-span-full text-center py-12 text-gray-500">
                            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-purple-500 mx-auto mb-4">
                            </div>
                            Loading languages...
                        </div>
                    </div>
                </section>

            </main>
        </div>

        <!-- Page Modal -->
        <div id="pageModal" class="modal">
            <div class="modal-content">
                <div class="bg-gradient-to-r from-green-500 to-emerald-500 text-white px-6 py-4 rounded-t-xl">
                    <h3 class="text-2xl font-bold" id="pageModalTitle">New Page</h3>
                </div>
                <div class="p-6">
                    <input type="hidden" id="pageSlugOriginal">
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Title</label>
                        <input type="text" id="pageTitle" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg"
                            placeholder="Page Title">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">URL Slug</label>
                        <input type="text" id="pageSlug" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg"
                            placeholder="page-slug">
                        <p class="text-sm text-gray-500 mt-1">Will be accessible at: yoursite.com/slug</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Content (HTML allowed)</label>
                        <textarea id="pageContent" rows="12"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg font-mono text-sm"
                            placeholder="<p>Your content here...</p>"></textarea>
                    </div>

                    <!-- SEO Settings Section -->
                    <hr class="my-6 border-gray-200">
                    <h4 class="text-lg font-bold mb-4 text-gray-800">SEO Settings</h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Meta Title</label>
                            <input type="text" id="pageMetaTitle"
                                class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg"
                                placeholder="Page Title for SEO">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Keywords</label>
                            <input type="text" id="pageKeywords"
                                class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg"
                                placeholder="keyword1, keyword2">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Meta Description</label>
                        <textarea id="pageMetaDesc" rows="2" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg"
                            placeholder="Description for search engines"></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">OG Image URL</label>
                        <input type="text" id="pageOgImage" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg"
                            placeholder="https://yoursite.com/image.jpg">
                    </div>
                    <div class="flex justify-end gap-3">
                        <button onclick="closePageModal()"
                            class="px-6 py-2 border border-gray-300 rounded-lg font-semibold hover:bg-gray-50">Cancel</button>
                        <button onclick="savePage()"
                            class="bg-gradient-to-r from-green-500 to-emerald-500 text-white px-8 py-2 rounded-lg font-bold">Save
                            Page</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Blog Modal -->
        <div id="blogModal" class="modal">
            <div class="modal-content">
                <div class="bg-gradient-to-r from-blue-500 to-cyan-500 text-white px-6 py-4 rounded-t-xl">
                    <h3 class="text-2xl font-bold" id="blogModalTitle">New Blog Post</h3>
                </div>
                <div class="p-6">
                    <input type="hidden" id="blogId">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Title</label>
                            <input type="text" id="blogTitle" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">URL Slug</label>
                            <input type="text" id="blogSlug" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Excerpt (Short Summary)</label>
                        <textarea id="blogExcerpt" rows="2"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Content (HTML allowed)</label>
                        <textarea id="blogContent" rows="10"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg font-mono text-sm"></textarea>
                    </div>
                    <div class="border-t pt-4 mb-4">
                        <h4 class="font-bold mb-3">SEO Settings</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Meta Title</label>
                                <input type="text" id="blogMetaTitle"
                                    class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Meta Description</label>
                                <input type="text" id="blogMetaDesc"
                                    class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg">
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" id="blogPublished" class="w-5 h-5">
                            <span class="font-semibold">Published</span>
                        </label>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button onclick="closeBlogModal()"
                            class="px-6 py-2 border border-gray-300 rounded-lg font-semibold hover:bg-gray-50">Cancel</button>
                        <button onclick="saveBlog()"
                            class="bg-gradient-to-r from-blue-500 to-cyan-500 text-white px-8 py-2 rounded-lg font-bold">Save
                            Post</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Redirect Modal -->
        <div id="redirectModal" class="modal">
            <div class="modal-content">
                <div class="bg-gradient-to-r from-orange-500 to-red-500 text-white px-6 py-4 rounded-t-xl">
                    <h3 class="text-2xl font-bold">Add Redirect</h3>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">From URL</label>
                        <input type="text" id="redirectFrom" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg"
                            placeholder="/old-page">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">To URL</label>
                        <input type="text" id="redirectTo" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg"
                            placeholder="/new-page">
                    </div>
                    <div class="flex justify-end gap-3">
                        <button onclick="closeRedirectModal()"
                            class="px-6 py-2 border border-gray-300 rounded-lg font-semibold hover:bg-gray-50">Cancel</button>
                        <button onclick="saveRedirect()"
                            class="bg-gradient-to-r from-orange-500 to-red-500 text-white px-8 py-2 rounded-lg font-bold">Add
                            Redirect</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Translation Modal -->
        <div id="translationModal" class="modal">
            <div class="modal-content" style="max-width: 900px;">
                <div
                    class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-4 rounded-t-xl flex justify-between items-center">
                    <h3 class="text-2xl font-bold" id="translationModalTitle">Edit Translations</h3>
                    <button onclick="closeTranslationModal()" class="text-white hover:text-gray-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-6">
                    <input type="hidden" id="translationLangCode">

                    <div class="mb-4">
                        <div class="relative">
                            <input type="text" id="translSearch" placeholder="Search translation keys or text..."
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 shadow-sm">
                            <div class="absolute left-3 top-3.5 text-gray-400">🔍</div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 ml-1">Tips: Use <strong>Ctrl+F</strong> to find text faster.
                        </p>
                    </div>

                    <div id="translationEditorItems" class="space-y-4 max-h-[60vh] overflow-y-auto pr-2 custom-scrollbar">
                        <!-- Items injected here -->
                    </div>

                    <div class="flex justify-end gap-3 mt-6 pt-4 border-t sticky bottom-0 bg-white">
                        <button onclick="closeTranslationModal()"
                            class="px-6 py-2 border border-gray-300 rounded-lg font-semibold hover:bg-gray-50">Cancel</button>
                        <button onclick="saveTranslation()"
                            class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-2 rounded-lg font-bold shadow-md hover:shadow-lg transition-all">Save
                            Changes</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // TinyMCE Init Helper
            function initTinyMCE(selector) {
                tinymce.remove(selector);
                tinymce.init({
                    selector: selector,
                    height: 400,
                    menubar: false,
                    plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen table help',
                    toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | link | code',
                    setup: function (editor) {
                        editor.on('change', function () {
                            editor.save();
                        });
                    }
                });
            }

            // Navigation
            document.querySelectorAll('.sidebar-link').forEach(link => {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    showSection(this.dataset.section);
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

                if (section === 'translations') {
                    if (typeof loadTranslations === 'function') loadTranslations();
                }
            }

            // Toast
            function showToast(message, type = 'success') {
                const toast = document.createElement('div');
                toast.className = `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-2xl z-50 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white`;
                toast.textContent = message;
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 3000);
            }

            // Load Data
            document.addEventListener('DOMContentLoaded', loadAllData);

            function loadAllData() {
                fetch('admin-api.php?action=get_all_data')
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('stat-pages').textContent = data.pages.length;
                            document.getElementById('stat-blog').textContent = data.blog.length;
                            document.getElementById('stat-redirects').textContent = data.redirects.length;
                            renderPages(data.pages);
                            renderBlog(data.blog);
                            renderRedirects(data.redirects);
                        }
                    });
            }

            // Pages
            function renderPages(pages) {
                const tbody = document.getElementById('pagesList');
                if (!pages.length) {
                    tbody.innerHTML = '<tr><td colspan="3" class="px-6 py-4 text-center text-gray-500">No pages yet</td></tr>';
                    return;
                }
                tbody.innerHTML = pages.map(p => `
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 font-semibold">${p.title}</td>
            <td class="px-6 py-4 text-gray-600">/${p.slug}</td>
            <td class="px-6 py-4 text-right">
                <button onclick='editPage(${JSON.stringify(p)})' class="text-blue-600 hover:underline mr-3">Edit</button>
                <button onclick="deletePage('${p.slug}')" class="text-red-600 hover:underline">Delete</button>
            </td>
        </tr>
    `).join('');
            }

            function openPageModal() {
                document.getElementById('pageModalTitle').textContent = 'New Page';
                document.getElementById('pageSlugOriginal').value = '';
                document.getElementById('pageTitle').value = '';
                document.getElementById('pageSlug').value = '';
                document.getElementById('pageContent').value = '';
                document.getElementById('pageMetaTitle').value = '';
                document.getElementById('pageMetaDesc').value = '';
                document.getElementById('pageOgImage').value = '';
                document.getElementById('pageKeywords').value = '';
                document.getElementById('pageModal').classList.add('active');
                initTinyMCE('#pageContent');
            }

            function editPage(page) {
                document.getElementById('pageModalTitle').textContent = 'Edit Page';
                document.getElementById('pageSlugOriginal').value = page.slug;
                document.getElementById('pageTitle').value = page.title;
                document.getElementById('pageSlug').value = page.slug;
                document.getElementById('pageContent').value = page.content;
                document.getElementById('pageMetaTitle').value = page.metaTitle || '';
                document.getElementById('pageMetaDesc').value = page.metaDesc || '';
                document.getElementById('pageOgImage').value = page.ogImage || '';
                document.getElementById('pageKeywords').value = page.keywords || '';
                document.getElementById('pageModal').classList.add('active');
                initTinyMCE('#pageContent');
            }

            function closePageModal() {
                tinymce.remove('#pageContent');
                document.getElementById('pageModal').classList.remove('active');
            }

            function savePage() {
                tinymce.triggerSave();
                const formData = new FormData();
                formData.append('action', 'save_page');
                formData.append('slug', document.getElementById('pageSlug').value);
                formData.append('title', document.getElementById('pageTitle').value);
                formData.append('content', document.getElementById('pageContent').value);
                formData.append('metaTitle', document.getElementById('pageMetaTitle').value);
                formData.append('metaDesc', document.getElementById('pageMetaDesc').value);
                formData.append('ogImage', document.getElementById('pageOgImage').value);
                formData.append('keywords', document.getElementById('pageKeywords').value);

                fetch('admin-api.php', { method: 'POST', body: formData })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            showToast('Page saved!');
                            closePageModal();
                            loadAllData();
                        } else {
                            showToast(data.error, 'error');
                        }
                    });
            }

            function deletePage(slug) {
                if (!confirm('Delete this page?')) return;
                const formData = new FormData();
                formData.append('action', 'delete_page');
                formData.append('slug', slug);
                fetch('admin-api.php', { method: 'POST', body: formData })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            showToast('Page deleted!');
                            loadAllData();
                        }
                    });
            }

            // Blog
            function renderBlog(posts) {
                const tbody = document.getElementById('blogPostsList');
                if (!posts.length) {
                    tbody.innerHTML = '<tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">No posts yet</td></tr>';
                    return;
                }
                tbody.innerHTML = posts.map(p => `
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 font-semibold">${p.title}</td>
            <td class="px-6 py-4 text-gray-600">/${p.slug}</td>
            <td class="px-6 py-4 text-center">
                <span class="px-3 py-1 rounded-full text-xs font-semibold ${p.published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                    ${p.published ? 'Published' : 'Draft'}
                </span>
            </td>
            <td class="px-6 py-4 text-right">
                <button onclick='editBlog(${JSON.stringify(p).replace(/'/g, "&#39;")})' class="text-blue-600 hover:underline mr-3">Edit</button>
                <button onclick="deleteBlog('${p.id}')" class="text-red-600 hover:underline">Delete</button>
            </td>
        </tr>
    `).join('');
        }

        function openBlogModal() {
            document.getElementById('blogModalTitle').textContent = 'New Blog Post';
            document.getElementById('blogId').value = '';
            document.getElementById('blogTitle').value = '';
            document.getElementById('blogSlug').value = '';
            document.getElementById('blogContent').value = '';
            document.getElementById('blogExcerpt').value = '';
            document.getElementById('blogMetaTitle').value = '';
            document.getElementById('blogMetaDesc').value = '';
            document.getElementById('blogPublished').checked = false;
            document.getElementById('blogModal').classList.add('active');
            initTinyMCE('#blogContent');
        }

        function editBlog(post) {
            document.getElementById('blogModalTitle').textContent = 'Edit Blog Post';
            document.getElementById('blogId').value = post.id;
            document.getElementById('blogTitle').value = post.title;
            document.getElementById('blogSlug').value = post.slug;
            document.getElementById('blogContent').value = post.content;
            document.getElementById('blogExcerpt').value = post.excerpt || '';
            document.getElementById('blogMetaTitle').value = post.metaTitle || '';
            document.getElementById('blogMetaDesc').value = post.metaDesc || '';
            document.getElementById('blogPublished').checked = post.published;
            document.getElementById('blogModal').classList.add('active');
            initTinyMCE('#blogContent');
        }

        function closeBlogModal() {
            tinymce.remove('#blogContent');
            document.getElementById('blogModal').classList.remove('active');
        }

        function saveBlog() {
            tinymce.triggerSave();
            const id = document.getElementById('blogId').value;
            const formData = new FormData();
            formData.append('action', id ? 'update_blog' : 'save_blog');
            if (id) formData.append('id', id);
            formData.append('title', document.getElementById('blogTitle').value);
            formData.append('slug', document.getElementById('blogSlug').value);
            formData.append('content', document.getElementById('blogContent').value);
            formData.append('excerpt', document.getElementById('blogExcerpt').value);
            formData.append('metaTitle', document.getElementById('blogMetaTitle').value);
            formData.append('metaDesc', document.getElementById('blogMetaDesc').value);
            formData.append('published', document.getElementById('blogPublished').checked);

            fetch('admin-api.php', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showToast('Blog post saved!');
                        closeBlogModal();
                        loadAllData();
                    } else {
                        showToast(data.error, 'error');
                    }
                });
        }

        function deleteBlog(id) {
            if (!confirm('Delete this post?')) return;
            const formData = new FormData();
            formData.append('action', 'delete_blog');
            formData.append('id', id);
            fetch('admin-api.php', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showToast('Post deleted!');
                        loadAllData();
                    }
                });
        }

        // Redirects
        function renderRedirects(redirects) {
            const tbody = document.getElementById('redirectsList');
            if (!redirects.length) {
                tbody.innerHTML = '<tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">No redirects configured</td></tr>';
                return;
            }
            tbody.innerHTML = redirects.map((r, i) => `
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-mono">${r.from}</td>
                            <td class="px-6 py-4 font-mono">${r.to}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">${r.type || 301}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button onclick="deleteRedirect(${i})" class="text-red-600 hover:underline">Delete</button>
                            </td>
                        </tr>
                    `).join('');
        }

        function openRedirectModal() {
            document.getElementById('redirectFrom').value = '';
            document.getElementById('redirectTo').value = '';
            document.getElementById('redirectModal').classList.add('active');
        }

        function closeRedirectModal() {
            document.getElementById('redirectModal').classList.remove('active');
        }

        function saveRedirect() {
            const formData = new FormData();
            formData.append('action', 'add_redirect');
            formData.append('from', document.getElementById('redirectFrom').value);
            formData.append('to', document.getElementById('redirectTo').value);

            fetch('admin-api.php', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showToast('Redirect added!');
                        closeRedirectModal();
                        loadAllData();
                    }
                });
        }

        function deleteRedirect(index) {
            if (!confirm('Delete this redirect?')) return;
            const formData = new FormData();
            formData.append('action', 'delete_redirect');
            formData.append('index', index);
            fetch('admin-api.php', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showToast('Redirect deleted!');
                        loadAllData();
                    }
                });
        }

        // Settings
        function saveSettings() {
            const formData = new FormData();
            formData.append('action', 'save_settings');
            formData.append('siteName', document.getElementById('siteName').value);
            formData.append('siteTagline', document.getElementById('siteTagline').value);
            formData.append('headerCode', document.getElementById('headerCode').value);
            formData.append('footerCode', document.getElementById('footerCode').value);

            const logoFile = document.getElementById('logoUpload').files[0];
            const faviconFile = document.getElementById('faviconUpload').files[0];
            if (logoFile) formData.append('logo', logoFile);
            if (faviconFile) formData.append('favicon', faviconFile);

            fetch('admin-api.php', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if (data.success) showToast(data.message);
                    else showToast(data.error, 'error');
                });
        }

        function saveSEO() {
            const formData = new FormData();
            formData.append('action', 'save_seo');
            formData.append('homeMetaTitle', document.getElementById('homeMetaTitle').value);
            formData.append('homeMetaDesc', document.getElementById('homeMetaDesc').value);
            formData.append('homeMetaKeywords', document.getElementById('homeMetaKeywords').value);
            formData.append('ogTitle', document.getElementById('ogTitle').value);
            formData.append('ogDesc', document.getElementById('ogDesc').value);
            formData.append('ogImage', document.getElementById('ogImage').value);
            formData.append('schemaOrgName', document.getElementById('schemaOrgName').value);
            formData.append('schemaType', document.getElementById('schemaType').value);

            fetch('admin-api.php', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if (data.success) showToast(data.message);
                    else showToast(data.error, 'error');
                });
        }

        // TRANSLATIONS MANAGER LOGIC

        let allLanguagesData = {};

        function loadTranslations() {
            const listContainer = document.getElementById('languagesList');
            listContainer.innerHTML = '<div class="col-span-full text-center py-12 text-gray-500">Loading...</div>';

            fetch('admin-api.php', { method: 'POST', body: new URLSearchParams({ action: 'get_translations' }) })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        allLanguagesData = data.languages;
                        renderLanguagesList(data.languages);
                    } else {
                        showToast(data.error || 'Failed to load translations', 'error');
                    }
                })
                .catch(err => console.error(err));
        }

        function renderLanguagesList(languages) {
            const listContainer = document.getElementById('languagesList');
            listContainer.innerHTML = '';

            Object.keys(languages).forEach(code => {
                const lang = languages[code];
                const card = document.createElement('div');
                card.className = 'bg-white rounded-xl shadow-md p-6 hover:shadow-xl transition-all cursor-pointer border border-transparent hover:border-indigo-100 group';
                card.onclick = () => openTranslationModal(code);

                card.innerHTML = `
                            <div class="flex items-center justify-between mb-4">
                                <div class="text-4xl shadow-sm rounded-full p-2 bg-gray-50">${lang.flag}</div>
                                <span class="bg-indigo-50 text-indigo-600 text-xs px-2 py-1 rounded-full font-mono uppercase font-bold">${code}</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-1 group-hover:text-indigo-600 transition-colors">${lang.name}</h3>
                            <p class="text-gray-500 text-sm mb-4">${Object.keys(lang.translations || {}).length} translated strings</p>
                            <button class="w-full py-2 rounded-lg bg-gray-50 text-gray-600 font-semibold group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">
                                Edit Translations
                            </button>
                        `;
                listContainer.appendChild(card);
            });
        }

        function openTranslationModal(code) {
            const lang = allLanguagesData[code];
            if (!lang) return;

            document.getElementById('translationLangCode').value = code;
            document.getElementById('translationModalTitle').innerHTML = `Edit <span class="text-yellow-300 border-b-2 border-yellow-300">${lang.name}</span> Translations`;

            const container = document.getElementById('translationEditorItems');
            container.innerHTML = '';

            const translations = lang.translations || {};

            // Render basic inputs for each key
            Object.keys(translations).forEach(key => {
                const val = translations[key];
                const item = document.createElement('div');
                item.className = 'translation-item bg-gray-50 p-4 rounded-lg border border-gray-100 hover:border-indigo-200 transition-colors';

                // Better labels (convert snake_case to Title Case)
                const label = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());

                let inputField = '';
                if (val.length > 60 || key.includes('desc') || key.includes('answer') || key.includes('content') || key.includes('subtitle')) {
                    inputField = `<textarea name="transl_${key}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 outline-none transition-all" rows="3">${val}</textarea>`;
                } else {
                    inputField = `<input type="text" name="transl_${key}" value="${val.replace(/"/g, '&quot;')}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 outline-none transition-all">`;
                }

                item.innerHTML = `
                            <div class="flex justify-between items-center mb-2">
                                <label class="text-sm font-bold text-gray-700 font-mono text-indigo-900 bg-indigo-50 px-2 py-0.5 rounded">${key}</label>
                                <span class="text-xs text-gray-400">Length: <span class="char-count">${val.length}</span></span>
                            </div>
                            ${inputField}
                        `;
                container.appendChild(item);
            });

            // Search filter logic
            const searchInput = document.getElementById('translSearch');
            searchInput.value = '';
            searchInput.onkeyup = function () {
                const term = this.value.toLowerCase();
                document.querySelectorAll('.translation-item').forEach(el => {
                    const text = el.innerText.toLowerCase();
                    const val = el.querySelector('input, textarea').value.toLowerCase();
                    if (text.includes(term) || val.includes(term)) {
                        el.style.display = 'block';
                    } else {
                        el.style.display = 'none';
                    }
                });
            };

            document.getElementById('translationModal').classList.add('active');
        }

        function closeTranslationModal() {
            document.getElementById('translationModal').classList.remove('active');
        }

        function saveTranslation() {
            const code = document.getElementById('translationLangCode').value;
            const inputs = document.querySelectorAll('#translationEditorItems [name^="transl_"]');
            const data = {};

            inputs.forEach(input => {
                const key = input.name.replace('transl_', '');
                data[key] = input.value;
            });

            const formData = new FormData();
            formData.append('action', 'save_translation');
            formData.append('code', code);

            // Append array object
            for (let key in data) {
                formData.append(`translations[${key}]`, data[key]);
            }

            const btn = document.querySelector('#translationModal button[onclick="saveTranslation()"]');
            const originalText = btn.innerText;
            btn.innerText = 'Saving...';
            btn.disabled = true;

            fetch('admin-api.php', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(res => {
                    btn.innerText = originalText;
                    btn.disabled = false;
                    if (res.success) {
                        showToast('Translations saved successfully!');
                        // Update local data
                        allLanguagesData[code].translations = data;
                        closeTranslationModal();
                        renderLanguagesList(allLanguagesData); // update counts if needed
                    } else {
                        showToast(res.error || 'Save failed', 'error');
                    }
                })
                .catch(err => {
                    btn.innerText = originalText;
                    btn.disabled = false;
                    showToast('Connection error', 'error');
                });
        }



        // Auto-generate slug from title
        document.getElementById('blogTitle')?.addEventListener('input', function () {
            if (!document.getElementById('blogId').value) {
                const slug = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
                document.getElementById('blogSlug').value = slug;
            }
        });

        document.getElementById('pageTitle')?.addEventListener('input', function () {
            if (!document.getElementById('pageSlugOriginal').value) {
                const slug = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
                document.getElementById('pageSlug').value = slug;
            }
        });

        // Close modals on outside click
        window.onclick = function (event) {
            if (event.target.classList.contains('modal')) {
                event.target.classList.remove('active');
            }
        }
    </script>

</body>

</html>
<?php endif; ?>