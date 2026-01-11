# Struktur Project Instagram Downloader (PHP Native)

## Struktur Folder yang Disarankan

```
instagram-downloader/
├── index.php                 # Frontend utama (convert dari index.html)
├── admin.php                 # Admin panel (convert dari login.html)
├── download.php              # API handler untuk download (sudah ada)
├── config.php                # Konfigurasi aplikasi
├── .htaccess                 # Apache configuration
│
├── includes/                 # File-file PHP untuk include
│   ├── header.php           # Header template
│   ├── footer.php           # Footer template
│   ├── functions.php        # Helper functions
│   └── translations.php     # Translation array
│
├── assets/                   # Static assets
│   ├── css/                 # CSS files (jika ada custom CSS)
│   ├── js/                  # JavaScript files
│   │   └── main.js         # Main JavaScript
│   └── images/              # Images (logo, favicon, etc)
│
├── storage/                  # Data storage (JSON files)
│   ├── settings.json
│   ├── seo.json
│   ├── pages.json
│   ├── blog.json
│   └── redirects.json
│
└── README.md                 # Dokumentasi project

```

## File yang Sudah Dibuat

✅ **config.php** - Konfigurasi aplikasi
✅ **includes/functions.php** - Helper functions
✅ **includes/header.php** - Header template
✅ **includes/footer.php** - Footer template
✅ **includes/translations.php** - Translation array
✅ **assets/js/main.js** - JavaScript main file
✅ **storage/** - Folder untuk data storage

## File yang Perlu Dibuat

⏳ **index.php** - Convert dari index.html
⏳ **admin.php** - Convert dari login.html  
⏳ **.htaccess** - Apache configuration

## Catatan Penting

1. **Cobalt API** harus berjalan di `localhost:9000` (bisa diubah di config.php)
2. **Storage** menggunakan JSON files (bisa upgrade ke database nanti)
3. **Session** digunakan untuk admin login
4. **Multi-language** sudah diimplementasikan (EN/ID)

## Deployment ke VPS

1. Upload semua file ke server
2. Set permission folder `storage/` menjadi writable (chmod 755)
3. Pastikan PHP 7.4+ dan Apache/Nginx terinstall
4. Update `COBALT_API_URL` di config.php sesuai server
5. Update `APP_URL` di config.php
6. Pastikan Cobalt Tools berjalan di server
