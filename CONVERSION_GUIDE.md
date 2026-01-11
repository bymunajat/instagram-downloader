# Panduan Konversi HTML ke PHP Native

## ✅ File yang Sudah Dibuat

1. **config.php** - Konfigurasi aplikasi ✅
2. **includes/functions.php** - Helper functions ✅
3. **includes/header.php** - Header template ✅
4. **includes/footer.php** - Footer template ✅
5. **includes/translations.php** - Translation array ✅
6. **assets/js/main.js** - JavaScript main file ✅
7. **.htaccess** - Apache configuration ✅
8. **README.md** - Dokumentasi ✅
9. **STRUCTURE.md** - Struktur project ✅

## 📋 File yang Perlu Dikonversi

### 1. index.html → index.php

**Cara konversi:**
1. Ganti `<html>` dengan `<?php require_once 'includes/header.php'; ?>`
2. Ganti `</html>` dengan `<?php require_once 'includes/footer.php'; ?>`
3. Pindahkan JavaScript ke `assets/js/main.js` (sudah dilakukan)
4. Tambahkan script tag: `<script src="assets/js/main.js"></script>`

**Struktur file index.php:**
```php
<?php
// Handle language change
if (isset($_GET['lang']) && in_array($_GET['lang'], ['en', 'id'])) {
    setLanguage($_GET['lang']);
    header('Location: index.php');
    exit;
}

// Include header
require_once 'includes/header.php';
?>

<!-- Content dari index.html (main section) -->
<main class="container mx-auto px-4 py-16 md:py-20">
    <!-- ... konten dari index.html ... -->
</main>

<!-- JavaScript -->
<script src="assets/js/main.js"></script>

<?php require_once 'includes/footer.php'; ?>
```

### 2. login.html → admin.php

**Cara konversi:**
1. Tambahkan session check di awal
2. Handle login form dengan PHP
3. Gunakan functions.php untuk authentication
4. Simpan data ke storage dengan JSON

**Struktur file admin.php:**
```php
<?php
require_once 'config.php';
require_once 'includes/functions.php';

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin.php');
        exit;
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}

// Check if logged in
$isLoggedIn = isAdminLoggedIn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- ... head content ... -->
</head>
<body>
    <?php if (!$isLoggedIn): ?>
        <!-- Login Form -->
    <?php else: ?>
        <!-- Admin Dashboard -->
    <?php endif; ?>
</body>
</html>
```

## 🔧 Langkah-Langkah Deployment

1. **Backup file lama**
   ```bash
   cp index.html index.html.backup
   cp login.html login.html.backup
   ```

2. **Konversi file**
   - Edit index.html menjadi index.php
   - Edit login.html menjadi admin.php

3. **Test di local**
   ```bash
   php -S localhost:8000
   ```

4. **Deploy ke VPS**
   ```bash
   # Upload semua file
   # Set permission
   chmod 755 storage/
   chmod 644 config.php
   ```

5. **Update config.php**
   - Ganti APP_URL
   - Ganti COBALT_API_URL
   - Ganti ADMIN_PASSWORD

## 📝 Catatan Penting

1. **JavaScript sudah dipindahkan** ke `assets/js/main.js`
2. **Storage menggunakan JSON** - bisa upgrade ke database nanti
3. **Session untuk admin** login sudah dihandle
4. **Multi-language** sudah diimplementasikan

## ⚠️ Hal yang Perlu Diperhatikan

1. Pastikan folder `storage/` writable
2. Update `COBALT_API_URL` sesuai server
3. Ganti password admin default!
4. Disable error display di production
