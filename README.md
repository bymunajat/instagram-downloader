<<<<<<< HEAD
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
=======
# Product Requirements Document (PRD)
Project: Instagram Media Downloader Platform  
Version: 1.1  
Status: Approved  
Last Updated: 2026-01-10  

---

## 1. Project Overview

This project is a web-based Instagram media downloader that allows users to download images, videos, and carousel posts.
The platform is powered by the **cobalt.tools engine** for media processing and includes multi-language support
and an admin panel for website configuration, SEO management, and content control.

---

## 2. Objectives

- Provide a fast and reliable Instagram media downloader powered by cobalt.tools
- Support multiple media formats (image, video, carousel)
- Enable non-technical admins to manage content and SEO
- Support multiple languages with automatic content switching
- Build a scalable and maintainable system

---

## 3. User Roles

### 3.1 Public User

- Access downloader tools
- Download Instagram media
- Switch website language

### 3.2 Admin

- Manage website settings
- Control SEO metadata
- Edit pages and blog content
- Manage languages and redirects

---

## 4. Core User Features

| Feature               | Description                                           | Status    |
|-----------------------|-------------------------------------------------------|-----------|
| Image Download        | Download single Instagram images                      | Completed |
| Video Download        | Download single Instagram videos                      | Completed |
| Carousel Download     | Download multiple images/videos from one post         | Completed |
| Paste URL Button      | One-click paste Instagram link                        | Completed |
| Multi-Language UI     | Translate UI and content                              | Completed |
| Responsive Design     | Support desktop, tablet, and mobile                   | Completed |
| Cross-Browser Support | Chrome, Firefox, Edge compatibility                   | Completed |

---

## 5. Admin Panel Requirements

### 5.1 Website Settings

| Feature           | Description                                   |
|-------------------|-----------------------------------------------|
| Logo Upload       | Upload and replace website logo               |
| Favicon Upload    | Upload browser favicon                        |
| Header Management | Add or edit custom header content             |
| Footer Management | Manage footer text, links, or scripts         |

---

### 5.2 SEO Management

| Feature           | Description                                              |
|-------------------|----------------------------------------------------------|
| Website Name      | Global website name setting                              |
| Default Meta Tags | Meta title, description, OG tags                         |
| Page-Level SEO    | Unique meta title and description per page               |
| Schema Markup     | Auto-generated schema and structured data                |
| Tool Page Meta    | Separate SEO meta for each downloader page               |

---

### 5.3 Page & Content Management

| Feature        | Description                                                     |
|----------------|-----------------------------------------------------------------|
| Blog System    | Create and manage blog posts                                    |
| Custom Pages   | Create pages such as Privacy Policy or Terms                    |
| Tool Pages     | 6–7 editable pages (Video, Reels, Story, etc.)                  |
| Content Editor | Edit page content easily without code                           |
| Layout Control | Page structure fixed, content fully editable                    |

---

### 5.4 Language Management

| Feature                   | Description                                           |
|---------------------------|-------------------------------------------------------|
| Multi-Language Support    | Support 6–7 languages                                 |
| Auto Content Switching    | Change content automatically on language selection    |
| Language-Based URLs       | URL format `/en`, `/id`, `/ar`, etc.                  |
| Default Language Redirect | Redirect root domain to default language               |

---

### 5.5 Redirect Management

| Feature           | Description                                  |
|-------------------|----------------------------------------------|
| Page Redirect     | Redirect one page URL to another             |
| Language Redirect | Redirect domain to language-based URL        |

---

## 6. Media Processing Engine

- Primary media processing engine: **cobalt.tools**
- Used for:
  - Image download
  - Video download
  - Carousel (multi-media) extraction
- Engine is integrated via API-based workflow
- No PHP dependency
- Engine abstraction layer allows future replacement if required

---

## 7. Security Requirements

- Secure admin authentication
- Restricted admin-only access
- Input validation and sanitization
- Basic rate limiting for download requests

---

## 8. Technical Requirements

- Frontend: HTML, CSS (Tailwind), JavaScript
- Backend: API-based service
- Media Engine: **cobalt.tools**
- SEO-friendly URL structure
- Modular and scalable architecture
- Optimized performance

---

## 9. Testing & Quality Assurance

- Media download testing via cobalt.tools
- Cross-browser testing
- Responsive layout testing
- Language switching testing
- Admin permission testing

---

## 10. Deployment

- Production-ready hosting environment
- Environment-based configuration
- Error logging and monitoring
- Easy maintenance and updates

---

## 11. Out of Scope

- Public user authentication
- Payment or subscription system
- Advanced analytics dashboard

---

## 12. Acceptance Criteria

- All downloader features work using cobalt.tools engine
- Admin can manage content without technical knowledge
- SEO metadata works correctly per page
- Multi-language system works across all pages
- Redirect rules function as expected

---

## 13. Final Notes

This document defines the functional and technical requirements for the Instagram Media Downloader Platform.
Any changes, including engine replacement or upgrades, must be documented in a new PRD version.
>>>>>>> e999a9863fcbf8914950ecc2989b2d9bd73e6027
