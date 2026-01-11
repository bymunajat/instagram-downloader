<?php
/**
 * Admin API Endpoints
 * Handle AJAX requests for admin panel
 */

require_once 'config.php';
require_once 'includes/functions.php';

// Set JSON header
header('Content-Type: application/json');

// Check if user is logged in
if (!isAdminLoggedIn()) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

// Get action
$action = $_POST['action'] ?? $_GET['action'] ?? '';

// Handle different actions
switch ($action) {

    // ==========================================
    // DATA FETCHING
    // ==========================================
    case 'get_all_data':
        $pages = getStorageData(STORAGE_PAGES);
        $blog = getStorageData(STORAGE_BLOG);
        $redirects = getStorageData(STORAGE_REDIRECTS);

        // Default Languages
        $languages = [
            ['code' => 'en', 'name' => 'English'],
            ['code' => 'id', 'name' => 'Indonesia'],
            ['code' => 'es', 'name' => 'Spanish'],
            ['code' => 'fr', 'name' => 'French'],
            ['code' => 'de', 'name' => 'German'],
            ['code' => 'pt', 'name' => 'Portuguese'],
            ['code' => 'ja', 'name' => 'Japanese'],
        ];

        // Format pages for list
        $pagesList = [];
        foreach ($pages as $slug => $data) {
            $pagesList[] = [
                'slug' => $slug,
                'title' => $data['title'] ?? $slug,
                'content' => $data['content'] ?? ''
            ];
        }

        echo json_encode([
            'success' => true,
            'settings' => getWebsiteSettings(),
            'seo' => getSEOSettings(),
            'pages' => $pagesList,
            'blog' => $blog,
            'redirects' => $redirects,
            'languages' => $languages
        ]);
        break;

    // ==========================================
    // SETTINGS
    // ==========================================
    case 'save_settings':
        $settings = getWebsiteSettings();

        $settings['siteName'] = sanitize($_POST['siteName'] ?? '');
        $settings['siteTagline'] = sanitize($_POST['siteTagline'] ?? '');
        $settings['headerCode'] = $_POST['headerCode'] ?? '';
        $settings['footerCode'] = $_POST['footerCode'] ?? '';

        // Handle File Uploads (Logo)
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/assets/images/';
            if (!is_dir($uploadDir))
                mkdir($uploadDir, 0755, true);
            $ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
            $filename = 'logo.' . $ext;
            move_uploaded_file($_FILES['logo']['tmp_name'], $uploadDir . $filename);
            $settings['logo'] = 'assets/images/' . $filename;
        }

        // Handle File Uploads (Favicon)
        if (isset($_FILES['favicon']) && $_FILES['favicon']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/assets/images/';
            if (!is_dir($uploadDir))
                mkdir($uploadDir, 0755, true);
            $ext = pathinfo($_FILES['favicon']['name'], PATHINFO_EXTENSION);
            $filename = 'favicon.' . $ext;
            move_uploaded_file($_FILES['favicon']['tmp_name'], $uploadDir . $filename);
            $settings['favicon'] = 'assets/images/' . $filename;
        }

        if (saveStorageData(STORAGE_SETTINGS, $settings)) {
            echo json_encode(['success' => true, 'message' => 'Settings saved successfully!']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to save settings']);
        }
        break;

    // ==========================================
    // SEO
    // ==========================================
    case 'save_seo':
        $seo = [
            'homeMetaTitle' => sanitize($_POST['homeMetaTitle'] ?? ''),
            'homeMetaDesc' => sanitize($_POST['homeMetaDesc'] ?? ''),
            'homeMetaKeywords' => sanitize($_POST['homeMetaKeywords'] ?? ''),
            'ogTitle' => sanitize($_POST['ogTitle'] ?? ''),
            'ogDesc' => sanitize($_POST['ogDesc'] ?? ''),
            'ogImage' => sanitize($_POST['ogImage'] ?? ''),
            'schemaOrgName' => sanitize($_POST['schemaOrgName'] ?? ''),
            'schemaType' => sanitize($_POST['schemaType'] ?? '')
        ];

        if (saveStorageData(STORAGE_SEO, $seo)) {
            echo json_encode(['success' => true, 'message' => 'SEO settings saved successfully!']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to save SEO settings']);
        }
        break;

    // ==========================================
    // PAGES
    // ==========================================
    case 'save_page':
        $slug = sanitize($_POST['slug'] ?? '');
        $title = sanitize($_POST['title'] ?? '');
        $content = $_POST['content'] ?? '';
        $metaTitle = sanitize($_POST['metaTitle'] ?? '');
        $metaDesc = sanitize($_POST['metaDesc'] ?? '');
        $ogImage = sanitize($_POST['ogImage'] ?? '');
        $keywords = sanitize($_POST['keywords'] ?? '');

        if (!$slug) {
            echo json_encode(['success' => false, 'error' => 'Slug required']);
            exit;
        }

        $pages = getStorageData(STORAGE_PAGES);
        $pages[$slug] = [
            'title' => $title,
            'content' => $content,
            'metaTitle' => $metaTitle,
            'metaDesc' => $metaDesc,
            'ogImage' => $ogImage,
            'keywords' => $keywords
        ];

        saveStorageData(STORAGE_PAGES, $pages);
        echo json_encode(['success' => true]);
        break;

    case 'get_page_detail':
        $slug = $_POST['slug'] ?? '';
        $pages = getStorageData(STORAGE_PAGES);

        if (isset($pages[$slug])) {
            echo json_encode([
                'success' => true,
                'page' => [
                    'slug' => $slug,
                    'title' => $pages[$slug]['title'],
                    'content' => $pages[$slug]['content'],
                    'metaTitle' => $pages[$slug]['metaTitle'] ?? '',
                    'metaDesc' => $pages[$slug]['metaDesc'] ?? '',
                    'ogImage' => $pages[$slug]['ogImage'] ?? '',
                    'keywords' => $pages[$slug]['keywords'] ?? ''
                ]
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Page not found']);
        }
        break;

    case 'update_page_content':
        $slug = sanitize($_POST['slug'] ?? '');
        $content = $_POST['content'] ?? '';

        $pages = getStorageData(STORAGE_PAGES);
        if (isset($pages[$slug])) {
            $pages[$slug]['content'] = $content;
            saveStorageData(STORAGE_PAGES, $pages);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Page not found']);
        }
        break;

    case 'delete_page':
        $slug = sanitize($_POST['slug'] ?? '');
        $pages = getStorageData(STORAGE_PAGES);
        if (isset($pages[$slug])) {
            unset($pages[$slug]);
            saveStorageData(STORAGE_PAGES, $pages);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Page not found']);
        }
        break;

    // ==========================================
    // BLOG
    // ==========================================
    case 'save_blog':
        $title = sanitize($_POST['title'] ?? '');
        if (!$title) {
            echo json_encode(['success' => false, 'error' => 'Title required']);
            exit;
        }

        $blog = getStorageData(STORAGE_BLOG);
        $id = uniqid();
        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $title));
        $slug = trim($slug, '-');

        // Check duplicate slug
        foreach ($blog as $p) {
            if (isset($p['slug']) && $p['slug'] === $slug) {
                $slug .= '-' . time();
            }
        }

        $blog[] = [
            'id' => $id,
            'title' => $title,
            'slug' => $slug,
            'content' => '',
            'excerpt' => '',
            'metaTitle' => $title,
            'metaDesc' => '',
            'published' => false,
            'date' => date('Y-m-d H:i:s')
        ];

        saveStorageData(STORAGE_BLOG, $blog);
        echo json_encode(['success' => true, 'id' => $id]);
        break;

    case 'get_blog_detail':
        $id = $_POST['id'] ?? '';
        $blog = getStorageData(STORAGE_BLOG);
        $found = null;

        foreach ($blog as $post) {
            if ($post['id'] === $id) {
                $found = $post;
                break;
            }
        }

        if ($found) {
            echo json_encode(['success' => true, 'post' => $found]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Post not found']);
        }
        break;

    case 'update_blog':
        $id = $_POST['id'] ?? '';
        $title = sanitize($_POST['title'] ?? '');
        $slug = sanitize($_POST['slug'] ?? '');
        $content = $_POST['content'] ?? '';
        $excerpt = sanitize($_POST['excerpt'] ?? '');
        $metaTitle = sanitize($_POST['metaTitle'] ?? '');
        $metaDesc = sanitize($_POST['metaDesc'] ?? '');
        $published = (($_POST['published'] ?? 'false') === 'true' || $_POST['published'] === '1');

        $blog = getStorageData(STORAGE_BLOG);
        $updated = false;

        foreach ($blog as $key => $post) {
            if ($post['id'] === $id) {
                $blog[$key]['title'] = $title;
                $blog[$key]['slug'] = $slug ?: $post['slug'];
                $blog[$key]['content'] = $content;
                $blog[$key]['excerpt'] = $excerpt;
                $blog[$key]['metaTitle'] = $metaTitle;
                $blog[$key]['metaDesc'] = $metaDesc;
                $blog[$key]['published'] = $published;
                $blog[$key]['updated_at'] = date('Y-m-d H:i:s');
                $updated = true;
                break;
            }
        }

        if ($updated) {
            saveStorageData(STORAGE_BLOG, $blog);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Post not found']);
        }
        break;

    case 'delete_blog':
        $id = $_POST['id'] ?? '';
        $blog = getStorageData(STORAGE_BLOG);
        $blog = array_filter($blog, function ($p) use ($id) {
            return $p['id'] !== $id;
        });
        saveStorageData(STORAGE_BLOG, array_values($blog));
        echo json_encode(['success' => true]);
        break;

    // ==========================================
    // REDIRECTS
    // ==========================================
    case 'add_redirect':
        $from = sanitize($_POST['from'] ?? '');
        $to = sanitize($_POST['to'] ?? '');

        $redirects = getStorageData(STORAGE_REDIRECTS);
        $redirects[] = [
            'from' => $from,
            'to' => $to,
            'type' => 301
        ];

        saveStorageData(STORAGE_REDIRECTS, $redirects);
        echo json_encode(['success' => true]);
        break;

    case 'delete_redirect':
        $index = (int) $_POST['index'];
        $redirects = getStorageData(STORAGE_REDIRECTS);
        if (isset($redirects[$index])) {
            array_splice($redirects, $index, 1);
            saveStorageData(STORAGE_REDIRECTS, $redirects);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Redirect not found']);
        }
        break;

    // ==========================================
    // TRANSLATIONS
    // ==========================================
    case 'get_translations':
        $languages = getStorageData(STORAGE_PATH . 'languages.json');
        echo json_encode(['success' => true, 'languages' => $languages]);
        break;

    case 'save_translation':
        $code = sanitize($_POST['code'] ?? '');
        $translationsData = $_POST['translations'] ?? []; // Array of key => value

        if (!$code) {
            echo json_encode(['success' => false, 'error' => 'Language code required']);
            exit;
        }

        $languages = getStorageData(STORAGE_PATH . 'languages.json');
        if (isset($languages[$code])) {
            // Update translations only, preserve other metadata
            foreach ($translationsData as $key => $value) {
                // Determine if we need to sanitize. 
                // For safety, let's keep it minimal but allow some chars.
                // Actually, for translations, we might want valid HTML entities or raw text.
                // Let's rely on standard sanitization but maybe allow some typical punctuation.
                $languages[$code]['translations'][$key] = trim($value);
            }
            saveStorageData(STORAGE_PATH . 'languages.json', $languages);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Language not found']);
        }
        break;

    default:
        echo json_encode(['success' => false, 'error' => 'Invalid action']);
        break;
}
