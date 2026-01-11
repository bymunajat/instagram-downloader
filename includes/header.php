<?php
/**
 * Header Template
 */
if (!defined('APP_NAME')) {
    require_once __DIR__ . '/../config.php';
}
require_once __DIR__ . '/functions.php';

$currentLang = getCurrentLanguage();
$seoSettings = getSEOSettings();
$websiteSettings = getWebsiteSettings();
$siteName = $websiteSettings['siteName'] ?? APP_NAME;
$metaTitle = $seoSettings['homeMetaTitle'] ?? $siteName . ' - Download Instagram';
$metaDesc = $seoSettings['homeMetaDesc'] ?? 'Download Instagram videos, photos, reels & IGTV instantly. 100% Free & No Watermark.';
?>
<!DOCTYPE html>
<html lang="<?php echo $currentLang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($metaTitle); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($metaDesc); ?>">
    
    <!-- Open Graph -->
    <?php if (!empty($seoSettings['ogTitle'])): ?>
    <meta property="og:title" content="<?php echo htmlspecialchars($seoSettings['ogTitle']); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($seoSettings['ogDesc'] ?? $metaDesc); ?>">
    <?php if (!empty($seoSettings['ogImage'])): ?>
    <meta property="og:image" content="<?php echo htmlspecialchars($seoSettings['ogImage']); ?>">
    <?php endif; ?>
    <?php endif; ?>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        }
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .card-white {
            background: white;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }
        
        /* Animated gradient border for input */
        #instaUrl {
            background: linear-gradient(white, white) padding-box,
                        linear-gradient(135deg, #667eea, #764ba2, #f093fb, #667eea) border-box;
            border: 3px solid transparent;
            animation: gradient-rotate 3s linear infinite;
            background-size: 300% 300%;
        }
        
        @keyframes gradient-rotate {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        #instaUrl:focus {
            animation: gradient-rotate 1.5s linear infinite;
            box-shadow: 0 0 20px rgba(102, 126, 234, 0.5);
        }

        @keyframes bounce-in {
            0% { opacity: 0; transform: scale(0.9); }
            50% { transform: scale(1.02); }
            100% { opacity: 1; transform: scale(1); }
        }
        .animate-bounce-in {
            animation: bounce-in 0.4s ease-out;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .float-animation {
            animation: float 3s ease-in-out infinite;
        }
    </style>
    <?php if (!empty($websiteSettings['headerCode'])): ?>
    <?php echo $websiteSettings['headerCode']; ?>
    <?php endif; ?>
</head>
<body class="text-white min-h-screen">

<!-- Header -->
<header class="glass">
    <div class="container mx-auto px-4 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold"><?php echo htmlspecialchars($siteName); ?></h1>
            </div>
            
            <div class="flex items-center gap-6 text-sm">
                <a href="#faq" class="hover:text-white/80 transition">FAQ</a>
                <select id="langSelector" class="glass rounded-lg px-3 py-1.5 text-sm outline-none cursor-pointer">
                    <option value="en" <?php echo $currentLang === 'en' ? 'selected' : ''; ?>>EN</option>
                    <option value="id" <?php echo $currentLang === 'id' ? 'selected' : ''; ?>>ID</option>
                </select>
            </div>
        </div>
    </div>
</header>
