<?php
/**
 * Configuration File
 * Instagram Downloader Application
 */

// Application Settings
define('APP_NAME', 'MySeoFan');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://localhost'); // Change this for production

// Cobalt API Settings
define('COBALT_API_URL', 'http://localhost:9000');
define('COBALT_TIMEOUT', 30); // seconds

// Admin Settings
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'admin123'); // Change in production!

// Storage Settings (for admin panel)
define('STORAGE_PATH', __DIR__ . '/storage/');
define('STORAGE_SETTINGS', STORAGE_PATH . 'settings.json');
define('STORAGE_SEO', STORAGE_PATH . 'seo.json');
define('STORAGE_PAGES', STORAGE_PATH . 'pages.json');
define('STORAGE_BLOG', STORAGE_PATH . 'blog.json');
define('STORAGE_REDIRECTS', STORAGE_PATH . 'redirects.json');
define('STORAGE_LANGUAGES', STORAGE_PATH . 'languages.json');

// Session Settings
define('SESSION_NAME', 'igdownloader_session');
define('SESSION_LIFETIME', 3600); // 1 hour

// Security
define('CSRF_TOKEN_NAME', 'csrf_token');

// Language Settings
define('DEFAULT_LANGUAGE', 'en');
define('SUPPORTED_LANGUAGES', ['en', 'id', 'es', 'fr', 'de', 'pt', 'ja']);

// Error Reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start Session
if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_start();
}

// Create storage directory if not exists
if (!file_exists(STORAGE_PATH)) {
    mkdir(STORAGE_PATH, 0755, true);
}

// Timezone
date_default_timezone_set('Asia/Jakarta');
