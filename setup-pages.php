<?php
/**
 * One-time setup: Generate default pages and return JSON
 */

// Manual include since this is standalone
$storagePath = __DIR__ . '/storage';
$pagesFile = $storagePath . '/pages.json';

// Load existing pages
$pages = [];
if (file_exists($pagesFile)) {
    $content = file_get_contents($pagesFile);
    $pages = json_decode($content, true) ?: [];
}

$defaultPages = [
    'video-downloader' => [
        'title' => 'Instagram Video Downloader',
        'content' => '<h2>Download Instagram Videos</h2><p>Save any Instagram video to your device in high quality. Our Instagram video downloader works with all types of videos including feed posts and IGTV.</p><h3>How to Download Instagram Videos</h3><ol><li>Open Instagram and find the video you want to download</li><li>Copy the video URL from the share menu</li><li>Paste the URL in our downloader above</li><li>Click Download and save the video</li></ol><p>All videos are downloaded in their original quality without watermarks.</p>',
        'metaTitle' => 'Instagram Video Downloader - Download IG Videos Free',
        'metaDesc' => 'Download Instagram videos in high quality. Fast, free, and easy Instagram video downloader. Save IG videos without watermark.',
        'ogImage' => '',
        'keywords' => 'instagram video downloader, download ig video, save instagram video, ig video download'
    ],
    'photo-downloader' => [
        'title' => 'Instagram Photo Downloader',
        'content' => '<h2>Download Instagram Photos</h2><p>Save Instagram photos and images to your device with our free photo downloader. Download single photos or multiple images from carousel posts.</p><h3>Features</h3><ul><li>High quality photo downloads</li><li>No watermarks</li><li>Support for carousel posts</li><li>100% free to use</li></ul><p>Download Instagram photos quickly and easily without any limitations.</p>',
        'metaTitle' => 'Instagram Photo Downloader - Save IG Photos Free',
        'metaDesc' => 'Download Instagram photos in original quality. Free Instagram photo downloader tool. Save IG images without watermark.',
        'ogImage' => '',
        'keywords' => 'instagram photo downloader, download ig photo, save instagram image, ig photo download'
    ],
    'reels-downloader' => [
        'title' => 'Instagram Reels Downloader',
        'content' => '<h2>Download Instagram Reels</h2><p>Download Instagram Reels videos with our fast and free Reels downloader. Save any Reel to your device in HD quality.</p><h3>Why Choose Our Reels Downloader?</h3><ul><li>Download Reels in HD quality</li><li>No watermark on videos</li><li>Fast download speed</li><li>Works on all devices</li><li>Completely free</li></ul><p>Simply paste the Reels URL and download instantly!</p>',
        'metaTitle' => 'Instagram Reels Downloader - Download IG Reels HD',
        'metaDesc' => 'Download Instagram Reels in HD quality. Free Reels downloader without watermark. Save IG Reels to your device easily.',
        'ogImage' => '',
        'keywords' => 'instagram reels downloader, download ig reels, save instagram reels, reels video download'
    ],
    'story-downloader' => [
        'title' => 'Instagram Story Downloader',
        'content' => '<h2>Download Instagram Stories</h2><p>Save Instagram Stories before they disappear. Our Story downloader lets you download any public Instagram Story.</p><p><strong>Note:</strong> Due to Instagram API limitations, Story download feature is currently under development. This feature will be available soon.</p><h3>How It Will Work</h3><ol><li>View the Instagram Story you want to save</li><li>Copy the Story URL</li><li>Paste it in our downloader</li><li>Download the Story before it expires</li></ol>',
        'metaTitle' => 'Instagram Story Downloader - Save IG Stories',
        'metaDesc' => 'Download Instagram Stories before they expire. Free Instagram Story downloader - save IG stories to your device.',
        'ogImage' => '',
        'keywords' => 'instagram story downloader, download ig story, save instagram stories, ig story download'
    ],
    'igtv-downloader' => [
        'title' => 'Instagram IGTV Downloader',
        'content' => '<h2>Download Instagram IGTV Videos</h2><p>Download long-form IGTV videos from Instagram. Our IGTV downloader supports all video lengths and qualities.</p><h3>IGTV Download Features</h3><ul><li>Download full-length IGTV videos</li><li>HD quality downloads</li><li>No file size limits</li><li>Fast download speed</li><li>Free forever</li></ul><p>Perfect for saving educational content, tutorials, and entertainment videos from Instagram IGTV.</p>',
        'metaTitle' => 'Instagram IGTV Downloader - Download IGTV Videos',
        'metaDesc' => 'Download Instagram IGTV videos in HD quality. Free IGTV downloader - save long IGTV videos to your device.',
        'ogImage' => '',
        'keywords' => 'instagram igtv downloader, download igtv, save igtv video, igtv download'
    ],
    'carousel-downloader' => [
        'title' => 'Instagram Carousel Downloader',
        'content' => '<h2>Download Instagram Carousel Posts</h2><p>Download all photos and videos from Instagram carousel posts. Our carousel downloader saves all media from multi-image posts.</p><h3>Carousel Download Features</h3><ul><li>Download all images from carousel</li><li>Save videos in carousel posts</li><li>Download each slide individually</li><li>High quality downloads</li><li>Batch download support</li></ul><p>Easily download complete carousel posts with all their photos and videos.</p>',
        'metaTitle' => 'Instagram Carousel Downloader - Download Multiple Photos',
        'metaDesc' => 'Download Instagram carousel posts with all photos and videos. Free carousel downloader for IG multi-image posts.',
        'ogImage' => '',
        'keywords' => 'instagram carousel downloader, download carousel post, save instagram carousel, ig carousel download'
    ],
    'highlights-downloader' => [
        'title' => 'Instagram Highlights Downloader',
        'content' => '<h2>Download Instagram Highlights</h2><p>Save Instagram Story Highlights from any public profile. Download permanent Stories that users have saved as Highlights.</p><p><strong>Note:</strong> Highlights download feature is under development and will be available in a future update.</p><h3>What Are Instagram Highlights?</h3><p>Highlights are permanent Stories that remain on a user\'s profile. Unlike regular Stories that disappear after 24 hours, Highlights can be viewed anytime.</p><p>Our Highlights downloader will allow you to save these precious moments.</p>',
        'metaTitle' => 'Instagram Highlights Downloader - Save IG Highlights',
        'metaDesc' => 'Download Instagram Highlights from any profile. Free Highlights downloader - save permanent Instagram Stories.',
        'ogImage' => '',
        'keywords' => 'instagram highlights downloader, download ig highlights, save instagram highlights, highlights download'
    ]
];

$created = [];
$existing = [];

// Add default pages
foreach ($defaultPages as $slug => $data) {
    if (!isset($pages[$slug])) {
        $pages[$slug] = $data;
        $created[] = $slug;
    } else {
        $existing[] = $slug;
    }
}

// Save
if (!file_exists($storagePath)) {
    mkdir($storagePath, 0755, true);
}

file_put_contents($pagesFile, json_encode($pages, JSON_PRETTY_PRINT));

// Return JSON response
header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'created' => $created,
    'existing' => $existing,
    'message' => count($created) . ' pages created, ' . count($existing) . ' already existed'
]);
