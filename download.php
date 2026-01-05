<?php
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
if(!isset($input['url'])) {
    echo json_encode(['status'=>'error','error'=>['code'=>'missing_url']]);
    exit;
}

$instaUrl = $input['url'];
$apiUrl = 'http://localhost:9000'; // pastikan cobalt API jalan

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['url'=>$instaUrl]));

$response = curl_exec($ch);
curl_close($ch);

echo $response;
