# Instagram Downloader - PHP Native Application

Aplikasi web untuk download media Instagram (video, foto, carousel) menggunakan PHP native dan Cobalt Tools API.

## Fitur

- ✅ Download video Instagram
- ✅ Download foto Instagram  
- ✅ Download carousel/post multiple
- ✅ Multi-language (EN/ID)
- ✅ Admin Panel untuk management
- ✅ Modern UI dengan Tailwind CSS
- ✅ Responsive design

## Requirements

- PHP 7.4 atau lebih tinggi
- Apache/Nginx web server
- Cobalt Tools API (harus running di `localhost:9000` atau server lain)
- mod_rewrite enabled (untuk Apache)

## Instalasi

1. **Clone atau download project**
   ```bash
   git clone [repository-url]
   cd instagram-downloader
   ```

2. **Set permissions**
   ```bash
   chmod 755 storage/
   chmod 644 config.php
   ```

3. **Konfigurasi**
   
   Edit file `config.php`:
   ```php
   define('APP_URL', 'https://yourdomain.com');
   define('COBALT_API_URL', 'http://localhost:9000');
   define('ADMIN_PASSWORD', 'your-secure-password'); // Ganti password default!
   ```

4. **Setup Cobalt Tools**
   
   Pastikan Cobalt Tools API berjalan. Untuk production, ubah `COBALT_API_URL` di config.php.

5. **Deploy ke VPS**
   - Upload semua file ke server
   - Pastikan PHP dan Apache/Nginx sudah terinstall
   - Set permission folder `storage/` menjadi writable
   - Update config.php sesuai environment

## Struktur Project

```
instagram-downloader/
├── index.php                 # Frontend utama
├── admin.php                 # Admin panel
├── download.php              # API handler
├── config.php                # Konfigurasi
├── .htaccess                 # Apache config
├── includes/                 # PHP includes
│   ├── header.php
│   ├── footer.php
│   ├── functions.php
│   └── translations.php
├── assets/                   # Static files
│   └── js/
│       └── main.js
└── storage/                  # Data storage (JSON)
    ├── settings.json
    ├── seo.json
    └── ...
```

## Default Login

- **Username:** `admin`
- **Password:** `admin123` (GANTI di production!)

## Konfigurasi Cobalt API

Default: `http://localhost:9000`

Untuk production, pastikan:
1. Cobalt Tools sudah terinstall di server
2. Update `COBALT_API_URL` di config.php
3. Pastikan API accessible dari aplikasi

## Development

Untuk development local:
```bash
# Start PHP built-in server
php -S localhost:8000

# Atau gunakan Laragon/XAMPP
# Pastikan Cobalt Tools running di localhost:9000
```

## Security Notes

⚠️ **PENTING untuk Production:**

1. **Ganti password admin** di config.php
2. **Disable error display** di config.php:
   ```php
   error_reporting(0);
   ini_set('display_errors', 0);
   ```
3. **Gunakan HTTPS** untuk production
4. **Set permission yang benar** untuk folder storage
5. **Backup data** secara rutin

## License

Private - All Rights Reserved

## Support

Untuk bantuan atau pertanyaan, hubungi developer.
