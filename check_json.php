<?php
$file = 'storage/languages.json';
if (!file_exists($file)) {
    echo "File not found\n";
    exit(1);
}
$content = file_get_contents($file);
$json = json_decode($content, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo "JSON Error: " . json_last_error_msg() . "\n";
    exit(1);
}

echo "JSON is valid.\n";

$langs = ['en', 'id'];
$required_keys = ['hero_subtitle', 'faq6_q', 'faq6_a'];

foreach ($langs as $lang) {
    if (!isset($json[$lang])) {
        echo "Missing language: $lang\n";
        continue;
    }
    foreach ($required_keys as $key) {
        if (!isset($json[$lang]['translations'][$key])) {
            echo "Missing key '$key' in '$lang'\n";
        }
    }
}
