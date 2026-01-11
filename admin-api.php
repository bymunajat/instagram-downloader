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
    case 'save_settings':
        $settings = [
            'siteName' => sanitize($_POST['siteName'] ?? ''),
            'siteTagline' => sanitize($_POST['siteTagline'] ?? ''),
            'headerCode' => $_POST['headerCode'] ?? '',
            'footerCode' => $_POST['footerCode'] ?? ''
        ];
        
        if (saveStorageData(STORAGE_SETTINGS, $settings)) {
            echo json_encode(['success' => true, 'message' => 'Settings saved successfully!']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to save settings']);
        }
        break;
        
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
        
    case 'get_settings':
        echo json_encode([
            'success' => true,
            'settings' => getWebsiteSettings(),
            'seo' => getSEOSettings()
        ]);
        break;
        
    default:
        echo json_encode(['success' => false, 'error' => 'Invalid action']);
        break;
}
