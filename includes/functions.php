<?php
/**
 * Helper Functions
 */

/**
 * Sanitize input
 */
function sanitize($data)
{
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

/**
 * Get JSON data from storage
 */
function getStorageData($file)
{
    if (file_exists($file)) {
        $content = file_get_contents($file);
        return json_decode($content, true) ?: [];
    }
    return [];
}

/**
 * Save JSON data to storage
 */
function saveStorageData($file, $data)
{
    $dir = dirname($file);
    if (!file_exists($dir)) {
        mkdir($dir, 0755, true);
    }
    return file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
}

/**
 * Check if user is logged in as admin
 */
function isAdminLoggedIn()
{
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

/**
 * Require admin login
 */
function requireAdminLogin()
{
    if (!isAdminLoggedIn()) {
        header('Location: admin.php');
        exit;
    }
}

/**
 * Get current language
 */
function getCurrentLanguage()
{
    return $_SESSION['language'] ?? DEFAULT_LANGUAGE;
}

/**
 * Set language
 */
function setLanguage($lang)
{
    if (in_array($lang, SUPPORTED_LANGUAGES)) {
        $_SESSION['language'] = $lang;
        return true;
    }
    return false;
}

/**
 * Get translation
 */
function t($key, $lang = null)
{
    global $translations;

    // Use global translations if available (populated by index.php)
    if (!empty($translations) && isset($translations[$key])) {
        return $translations[$key];
    }

    // Fallback: Load specifically if not set (e.g. inside other scripts)
    // Note: This matches the logic we added to index.php
    // Ideally index.php sets the global $translations correctly.
    // If key not found, return key (cleaner than checking file every time)
    return $translations[$key] ?? $key;
}

/**
 * Generate CSRF Token
 */
function generateCSRFToken()
{
    if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

/**
 * Verify CSRF Token
 */
function verifyCSRFToken($token)
{
    return isset($_SESSION[CSRF_TOKEN_NAME]) && hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}

/**
 * Get website settings
 */
function getWebsiteSettings()
{
    return getStorageData(STORAGE_SETTINGS);
}

/**
 * Get SEO settings
 */
function getSEOSettings()
{
    return getStorageData(STORAGE_SEO);
}

/**
 * Get page content
 */
function getPageContent($pageId)
{
    $pages = getStorageData(STORAGE_PAGES);
    return $pages[$pageId] ?? null;
}

/**
 * Get blog posts
 */
function getBlogPosts()
{
    return getStorageData(STORAGE_BLOG);
}

/**
 * Get redirects
 */
function getRedirects()
{
    return getStorageData(STORAGE_REDIRECTS);
}
