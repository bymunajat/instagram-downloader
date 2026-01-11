<?php
/**
 * Footer Template
 */
$websiteSettings = getWebsiteSettings();
$siteName = $websiteSettings['siteName'] ?? APP_NAME;
?>
<!-- Footer -->
<footer class="glass mt-12">
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <span class="font-bold"><?php echo htmlspecialchars($siteName); ?></span>
            </div>
            
            <div class="text-sm text-white/80">
                © <?php echo date('Y'); ?> <?php echo htmlspecialchars($siteName); ?> - Instagram Downloader
            </div>
            
            <div class="flex gap-3">
                <a href="#" class="w-9 h-9 glass rounded-full flex items-center justify-center hover:bg-white/20 transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"></path></svg>
                </a>
                <a href="#" class="w-9 h-9 glass rounded-full flex items-center justify-center hover:bg-white/20 transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37zm1.5-4.87h.01"></path></svg>
                </a>
                <a href="#" class="w-9 h-9 glass rounded-full flex items-center justify-center hover:bg-white/20 transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c5.05-.5 9-4.76 9-9.95z"></path></svg>
                </a>
            </div>
        </div>
    </div>
</footer>

<?php if (!empty($websiteSettings['footerCode'])): ?>
<?php echo $websiteSettings['footerCode']; ?>
<?php endif; ?>

</body>
</html>
