# Perbaikan Masalah Storage Railway - Foto Hilang

## ⚠️ Masalah yang Ditemukan

1. **Foto Hilang Setelah Restart**: Foto yang sudah diupload tiba-tiba hilang
2. **Error 403 Forbidden**: File foto tidak bisa diakses (permission issue)
3. **Error Access Control Checks**: AJAX requests gagal

## 🔍 Root Cause

### 1. Railway Ephemeral Storage
Railway menggunakan **ephemeral storage** yang berarti:
- File yang disimpan di `storage/app/public` akan **HILANG** saat container restart
- Database tetap menyimpan record, tapi file fisik hilang
- Ini menyebabkan foto "hilang" meskipun masih ada di database

### 2. File Permission Issues
- File yang diupload tidak memiliki permission yang benar (644)
- Web server tidak bisa membaca file (403 Forbidden)

## ✅ Solusi yang Diterapkan

### 1. Fix File Permissions
- ✅ **Entrypoint Script**: Otomatis fix permission saat container start
- ✅ **Artisan Command**: `php artisan fotos:fix-permissions` untuk fix permission file yang sudah ada
- ✅ **Upload Handler**: Set permission 644 setelah upload

### 2. Fix Access Control Errors
- ✅ **XMLHttpRequest**: Ganti semua `fetch()` dengan `XMLHttpRequest` untuk menghindari access control issues
- ✅ **API Headers**: Tambahkan proper headers (Content-Type, CORS) di semua API endpoints
- ✅ **Middleware**: Pastikan middleware return JSON untuk AJAX requests

### 3. Storage Persistence (PENTING!)

**Railway Ephemeral Storage tidak persistent!** Anda perlu:

#### Opsi 1: Railway Volume (Recommended)
1. Buka Railway Dashboard
2. Pilih service Anda
3. Klik "Volumes" tab
4. Create new volume dengan path: `/app/storage/app/public`
5. Mount volume ke service

#### Opsi 2: Cloud Storage (S3/Cloudinary)
Gunakan external storage seperti:
- AWS S3
- Cloudinary
- DigitalOcean Spaces
- Google Cloud Storage

Update `config/filesystems.php`:
```php
'disks' => [
    's3' => [
        'driver' => 's3',
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION'),
        'bucket' => env('AWS_BUCKET'),
    ],
],
```

## 🔧 Perbaikan yang Sudah Dilakukan

### 1. Entrypoint Script (`docker/entrypoint.sh`)
```bash
# Fix permissions for all existing files
find storage/app/public/fotos -type f -exec chmod 644 {} \;
find public/storage/fotos -type f -exec chmod 644 {} \;

# Run fix permissions command
php artisan fotos:fix-permissions || true
```

### 2. Artisan Command (`app/Console/Commands/FixPhotoPermissions.php`)
- Fix permission untuk semua file foto yang ada
- Check file yang missing
- Report status

### 3. API Endpoints
Semua API endpoints sekarang return JSON dengan proper headers:
- `Content-Type: application/json`
- `Access-Control-Allow-Origin: *`
- `Access-Control-Allow-Methods: GET, POST, OPTIONS`
- `Access-Control-Allow-Headers: Content-Type, X-Requested-With`

### 4. Upload Handler
Set permission 644 setelah upload:
```php
chmod(Storage::disk('public')->path($path), 0644);
```

## 📋 Langkah Selanjutnya (PENTING!)

### 1. Setup Railway Volume (WAJIB!)
Tanpa volume, foto akan hilang setiap restart:

1. Buka Railway Dashboard
2. Pilih service
3. Klik "Volumes"
4. Create volume dengan:
   - **Name**: `storage-volume`
   - **Mount Path**: `/app/storage/app/public`
   - **Size**: Sesuai kebutuhan (min 1GB)

### 2. Atau Setup Cloud Storage
Jika tidak ingin pakai volume, setup S3/Cloudinary:

1. Buat akun AWS S3 atau Cloudinary
2. Update `.env`:
   ```
   FILESYSTEM_DISK=s3
   AWS_ACCESS_KEY_ID=your_key
   AWS_SECRET_ACCESS_KEY=your_secret
   AWS_DEFAULT_REGION=your_region
   AWS_BUCKET=your_bucket
   ```
3. Update `config/filesystems.php` untuk menggunakan S3

### 3. Run Fix Permissions
Setelah deploy, jalankan:
```bash
php artisan fotos:fix-permissions
```

## 🧪 Testing

1. **Upload Foto**: Upload foto baru
2. **Check Permission**: Pastikan file bisa diakses (tidak 403)
3. **Restart Container**: Restart Railway service
4. **Check Foto**: Pastikan foto masih ada (jika pakai volume)

## ⚠️ Catatan Penting

- **Tanpa Volume/Cloud Storage**: Foto akan hilang setiap restart!
- **Database tetap ada**: Record foto masih ada di database, tapi file hilang
- **Volume wajib**: Setup Railway Volume untuk persistent storage
- **Permission penting**: File harus memiliki permission 644 untuk bisa diakses

## 📝 File yang Dimodifikasi

1. `docker/entrypoint.sh` - Fix permissions saat startup
2. `app/Console/Commands/FixPhotoPermissions.php` - Command untuk fix permissions
3. `app/Http/Controllers/FotoController.php` - Set permission setelah upload
4. `app/Http/Controllers/Web/AdminController.php` - Fix API response headers
5. `resources/views/admin/fotos.blade.php` - Ganti fetch dengan XMLHttpRequest

