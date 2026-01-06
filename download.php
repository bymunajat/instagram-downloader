<?php

// ===== HANDLE DIRECT DOWNLOAD (GET) =====
if (
    isset($_GET['action']) &&
    $_GET['action'] === 'download' &&
    !empty($_GET['url'])
) {
    $url = urldecode($_GET['url']);
    $filename = 'instagram_media_' . time();

    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');

    readfile($url);
    exit;
}

// ===== COBALT API HANDLER (POST) =====
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['url'])) {
    echo json_encode([
        'status' => 'error',
        'error' => ['code' => 'missing_url']
    ]);
    exit;
}

$instaUrl = $input['url'];
$apiUrl = 'http://localhost:9000';

$ch = curl_init($apiUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Accept: application/json'
    ],
    CURLOPT_POSTFIELDS => json_encode(['url' => $instaUrl])
]);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

// ===== SINGLE VIDEO =====
if (isset($data['status']) && $data['status'] === 'redirect') {
    echo json_encode([
        'status' => 'single',
        'type' => 'video',
        'url' => $data['url']
    ]);
    exit;
}

// ===== MULTIPLE MEDIA =====
if (isset($data['media']) && is_array($data['media'])) {
    echo json_encode([
        'status' => 'multiple',
        'media' => $data['media']
    ]);
    exit;
}

// ===== ERROR =====
echo json_encode([
    'status' => 'error',
    'error' => 'Unsupported or invalid Instagram URL'
]);
