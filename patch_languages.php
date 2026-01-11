<?php

$file = 'storage/languages.json';
if (!file_exists($file)) {
    die("File not found");
}

$json = json_decode(file_get_contents($file), true);

if (json_last_error() !== JSON_ERROR_NONE) {
    die("JSON Error: " . json_last_error_msg());
}

// Define the keys and values to act as defaults (English).
// Ideally we would translate these, but for now we stop the errors by providing English fallbacks 
// or basic translations where obvious.
$defaults = [
    'hero_subtitle' => 'Download Photos, Videos, Reels, IGTV & Carousel',
    'intro_title' => 'Download Instagram Photos',
    'intro_text' => 'FastDL is your go-to tool for downloading Instagram content. Whether it\'s a memorable photo, an inspiring video, or an entertaining Reel, our service allows you to save high-quality media directly to your device. Compatible with PC, Mac, Android, and iPhone, FastDL makes saving Instagram memories simple, fast, and free.',

    'choose_title' => 'Why Choose FastDL?',
    'choose_subtitle' => 'The fastest, safest, and most reliable Instagram downloader.',
    'choose_fast_title' => 'Fast Download',
    'choose_fast_desc' => 'Optimized servers ensure you get your content in seconds.',
    'choose_support_title' => 'All Devices Supported',
    'choose_support_desc' => 'Works perfectly on Mobile, Tablet, and Desktop browsers.',
    'choose_qa_title' => 'Original Quality',
    'choose_qa_desc' => 'Download media in its original high resolution without compression.',
    'choose_sec_title' => 'Secure & Safe',
    'choose_sec_desc' => 'No data retention. Your downloading activity is private and secure.',

    'features_title' => 'FastDL App Features',
    'features_subtitle' => 'Comprehensive tools for all your Instagram downloading needs.',
    'feat_video_title' => 'Video Downloader',
    'feat_video_desc' => 'Save Instagram videos in MP4 format with the best quality available.',
    'feat_photo_title' => 'Photo Downloader',
    'feat_photo_desc' => 'Download high-resolution photos from any public Instagram post.',
    'feat_reels_title' => 'Reels Downloader',
    'feat_reels_desc' => 'Download short-form Reels videos to watch offline anytime.',
    'feat_igtv_title' => 'IGTV Downloader',
    'feat_igtv_desc' => 'Save long-form IGTV videos to your device for later viewing.',
    'feat_carousel_title' => 'Carousel Downloader',
    'feat_carousel_desc' => 'Download entire albums (carousels) with multiple photos and videos in one go.'
];

// Specific overrides if needed (optional simple translations could go here, 
// but sticking to English for safety to ensure file validity first).
// We will just apply the defaults to missing keys.

$languages = ['es', 'fr', 'de', 'pt', 'ja', 'id'];

foreach ($json as $langCode => &$data) {
    if (!isset($data['translations'])) {
        $data['translations'] = [];
    }

    // fix copyright
    if (isset($data['translations']['copyright'])) {
        $data['translations']['copyright'] = str_replace('IGSaver', 'FastDL', $data['translations']['copyright']);
    }

    foreach ($defaults as $key => $val) {
        // If key is missing, add it
        if (!isset($data['translations'][$key])) {
            $data['translations'][$key] = $val;
        }
    }
}

// Save back
file_put_contents($file, json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "Languages patched successfully.\n";
