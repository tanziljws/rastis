# Perbaikan Lengkap CORS & Storage untuk Railway

## ✅ Perbaikan yang Telah Diterapkan

### 1. **CORS Configuration** (`config/cors.php`)
- ✅ Paths: `api/*`, `sanctum/csrf-cookie`, `admin/*`, `admin/api/*`
- ✅ Allowed methods: `*` (GET, POST, PUT, DELETE, OPTIONS)
- ✅ Allowed origins: `*` (untuk development dan Railway)
- ✅ Allowed headers: `*`
- ✅ Supports credentials: `true`

### 2. **Middleware CORS** (`bootstrap/app.php`)
- ✅ Enable CORS untuk API routes
- ✅ Enable CORS untuk Web routes (admin)
- ✅ HandleCors middleware aktif

### 3. **Filesystem Configuration** (`config/filesystems.php`)
- ✅ Default disk: `public` (bukan `local`)
- ✅ Public disk URL: `{APP_URL}/storage`
- ✅ Symbolic link: `public/storage` → `storage/app/public`

### 4. **Storage Link & Permissions** (`docker/entrypoint.sh`)
- ✅ Auto create storage link saat startup
- ✅ Fix permissions untuk semua file (644) dan folder (755)
- ✅ Create .htaccess untuk allow access
- ✅ Run `fotos:fix-permissions` command

### 5. **API Endpoints Headers**
Semua API endpoints sekarang return dengan proper headers:
- ✅ `Content-Type: application/json`
- ✅ `Access-Control-Allow-Origin: *`
- ✅ `Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS`
- ✅ `Access-Control-Allow-Headers: Content-Type, X-Requested-With`

## 🔧 Konfigurasi .env yang Diperlukan

Pastikan `.env` memiliki konfigurasi berikut:

```env
APP_URL=https://smkn4.up.railway.app
FILESYSTEM_DISK=public

# Database MySQL Railway
DB_CONNECTION=mysql
DB_HOST=shinkansen.proxy.rlwy.net
DB_PORT=47127
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=TDSlkZCKbgTqMziVslGmJoBmxOroOBxh
```

## 📁 Struktur Storage

```
storage/
├── app/
│   └── public/
│       ├── fotos/              # Foto original
│       │   └── thumbnails/     # Thumbnail
│       ├── hero-backgrounds/    # Hero background images
│       └── logos/              # Logo sekolah
│
public/
└── storage/                    # Symbolic link → storage/app/public
    ├── fotos/
    ├── hero-backgrounds/
    └── logos/
```

## 🚀 Setup Railway Volume (PENTING!)

### Tanpa Volume:
- ❌ File hilang saat container restart
- ❌ Error 403 karena file tidak ada
- ❌ Database tetap ada, tapi file fisik hilang

### Dengan Volume:
- ✅ File persistent
- ✅ Tidak hilang saat restart
- ✅ Semua foto tetap ada

### Cara Setup:
1. Buka Railway Dashboard: https://railway.app
2. Pilih service aplikasi Anda
3. Klik tab **"Volumes"**
4. Klik **"Create Volume"**
5. Isi:
   - **Name**: `storage-volume`
   - **Mount Path**: `/app/storage/app/public`
   - **Size**: 1GB+ (sesuai kebutuhan)
6. Klik **"Create"**

## 🧪 Testing

### 1. Test Storage Link
```bash
php artisan storage:link
ls -la public/storage  # Harus ada symbolic link
```

### 2. Test File Access
Upload foto, lalu cek:
- URL: `https://smkn4.up.railway.app/storage/fotos/filename.jpg`
- Harus return 200 OK, bukan 403

### 3. Test CORS
Buka browser console, cek:
- Tidak ada error "access control checks"
- AJAX requests berhasil
- File bisa di-load

## 📝 File yang Dimodifikasi

1. ✅ `config/cors.php` - CORS configuration (BARU)
2. ✅ `bootstrap/app.php` - Enable CORS middleware
3. ✅ `config/filesystems.php` - Default disk = public
4. ✅ `docker/entrypoint.sh` - Storage link & permissions
5. ✅ `app/Http/Controllers/FotoController.php` - API headers
6. ✅ `app/Http/Controllers/Web/AdminController.php` - API headers
7. ✅ `app/Console/Commands/FixPhotoPermissions.php` - Fix permissions command

## ⚠️ Catatan Penting

1. **Railway Volume WAJIB** untuk persistent storage
2. **Storage link** harus dibuat (`php artisan storage:link`)
3. **File permissions** harus 644 (file) dan 755 (folder)
4. **CORS** harus enable untuk AJAX requests
5. **APP_URL** harus sesuai dengan domain Railway

## 🔄 Setelah Deploy

Setelah push ke Railway, pastikan:
1. ✅ Storage link dibuat (otomatis di entrypoint)
2. ✅ Permissions fixed (otomatis di entrypoint)
3. ✅ Railway Volume mounted (setup manual di dashboard)
4. ✅ Test upload foto
5. ✅ Test hapus foto
6. ✅ Test load foto (tidak 403)

## 📚 Referensi

- [Laravel CORS Documentation](https://laravel.com/docs/11.x/routing#cors)
- [Laravel Filesystem Documentation](https://laravel.com/docs/11.x/filesystem)
- [Railway Volumes Documentation](https://docs.railway.app/guides/volumes)

