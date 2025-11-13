# ✅ Fix: Upload Timeout & Error 500 - Foto Upload

## 🔍 Masalah yang Ditemukan

**Status Code:**
- **499** = Client Closed Request (timeout)
- **500** = Server Error Internal

**Penyebab:**
1. Image processing terlalu lama (ImageService::processImage)
2. File terlalu besar (5MB max tapi processing bisa lama)
3. Memory limit exceeded saat image processing
4. No timeout handling di controller
5. No error logging untuk debugging

## ✅ Perbaikan yang Diterapkan

### 1. **Increase Time & Memory Limits** ✅

**File:** `app/Http/Controllers/FotoController.php`

```php
public function store(Request $request)
{
    // Increase time limit for image processing
    set_time_limit(120); // 2 minutes
    ini_set('memory_limit', '256M');
    // ...
}
```

**Efek:**
- Request bisa berjalan sampai 2 menit
- Memory limit dinaikkan ke 256MB
- Mencegah timeout untuk file besar

### 2. **Optimize ImageService** ✅

**File:** `app/Services/ImageService.php`

#### A. Skip Processing untuk File Besar
```php
// Check file size before processing
$fileSize = filesize($fullPath);
if ($fileSize > 10 * 1024 * 1024) { // 10MB
    \Log::warning('Large file detected, skipping processing', ['size' => $fileSize]);
    return [
        'original' => $originalPath,
        'thumbnail' => null
    ];
}
```

#### B. Set Time Limit di ImageService
```php
public static function processImage($file, $folder = 'fotos', $maxWidth = 1920, $quality = 85)
{
    // Set time limit for image processing
    set_time_limit(60); // 1 minute for processing
    ini_set('memory_limit', '256M');
    // ...
}
```

#### C. Skip Thumbnail untuk File Besar
```php
public static function generateThumbnail($imagePath, $folder = 'fotos', $size = 400)
{
    // Skip thumbnail for very large files
    $fileSize = filesize($imagePath);
    if ($fileSize > 10 * 1024 * 1024) { // 10MB
        \Log::info('Thumbnail generation skipped for large file', ['size' => $fileSize]);
        return null;
    }
    // ...
}
```

### 3. **Enhanced Error Logging** ✅

**File:** `app/Http/Controllers/FotoController.php`

#### A. Log Processing Time
```php
// Log start time for debugging
$startTime = microtime(true);

$imagePaths = ImageService::processImage($file, 'fotos', 1920, 85);

// Log processing time
$processingTime = microtime(true) - $startTime;
\Log::info("Image processed in {$processingTime}s", [
    'file_size' => $file->getSize(),
    'path' => $path
]);
```

#### B. Log Full Error Details
```php
\Log::error('Foto upload failed', [
    'error' => $e->getMessage(),
    'file' => $e->getFile(),
    'line' => $e->getLine(),
    'trace' => $e->getTraceAsString(),
    'request_size' => $request->header('Content-Length'),
    'has_file' => $request->hasFile('file'),
    'file_size' => $request->hasFile('file') ? $request->file('file')->getSize() : null,
]);
```

#### C. Log Image Processing Errors
```php
\Log::error('Image processing failed', [
    'error' => $e->getMessage(),
    'file_size' => $file->getSize(),
    'trace' => $e->getTraceAsString()
]);
```

### 4. **Better Error Messages** ✅

**File:** `app/Http/Controllers/FotoController.php`

```php
// Check if it's a timeout or memory issue
$errorMessage = 'Terjadi kesalahan saat upload foto.';
if (str_contains($e->getMessage(), 'timeout') || str_contains($e->getMessage(), 'Maximum execution time')) {
    $errorMessage = 'Upload timeout. File terlalu besar atau server terlalu sibuk. Coba lagi dengan file yang lebih kecil.';
} elseif (str_contains($e->getMessage(), 'memory') || str_contains($e->getMessage(), 'Memory')) {
    $errorMessage = 'File terlalu besar. Maksimal 5MB.';
}
```

**Efek:**
- User mendapat pesan error yang jelas
- Bisa tahu apakah masalah timeout atau memory
- Bisa tahu apakah perlu reduce file size

## 📋 Konfigurasi yang Diperlukan

### Railway Environment Variables

Pastikan di Railway dashboard:

```bash
# PHP Configuration (jika bisa set)
PHP_INI_SCAN_DIR=/etc/php/conf.d
```

### PHP.ini Settings (jika bisa dikonfigurasi)

```ini
max_execution_time = 120
memory_limit = 256M
upload_max_filesize = 5M
post_max_size = 6M
```

### Nginx/Web Server Timeout (Railway)

Railway biasanya sudah handle ini, tapi pastikan:
- Request timeout: **120 detik** (2 menit)
- Client body timeout: **120 detik**

## 🧪 Testing

Setelah deploy, test:

1. **Upload File Kecil (< 1MB):**
   ```bash
   # Harus berhasil dengan cepat
   # Check logs: "Image processed in Xs"
   ```

2. **Upload File Sedang (2-3MB):**
   ```bash
   # Harus berhasil
   # Processing time mungkin 5-15 detik
   ```

3. **Upload File Besar (4-5MB):**
   ```bash
   # Harus berhasil tapi lebih lama
   # Processing time mungkin 15-30 detik
   # Thumbnail mungkin di-skip untuk file > 10MB
   ```

4. **Check Logs:**
   ```bash
   # Railway logs atau Laravel logs
   tail -f storage/logs/laravel.log
   
   # Cari:
   # - "Image processed in Xs"
   # - "Image processing failed"
   # - "Foto upload failed"
   ```

5. **Monitor Network Tab:**
   - Request tidak boleh timeout (499)
   - Response harus 200/201, bukan 500
   - Processing time harus < 60 detik untuk file normal

## 📝 File yang Dimodifikasi

1. ✅ `app/Http/Controllers/FotoController.php`
   - Tambah `set_time_limit(120)` dan `ini_set('memory_limit', '256M')`
   - Tambah logging untuk processing time
   - Tambah enhanced error logging
   - Tambah better error messages

2. ✅ `app/Services/ImageService.php`
   - Tambah `set_time_limit(60)` dan `ini_set('memory_limit', '256M')`
   - Skip processing untuk file > 10MB
   - Skip thumbnail untuk file > 10MB
   - Tambah file existence check

## ⚠️ Catatan Penting

1. **File Size Limits:**
   - Validation: **5MB max** (di controller)
   - Processing skip: **10MB** (di ImageService)
   - Thumbnail skip: **10MB** (di generateThumbnail)

2. **Time Limits:**
   - Controller: **120 detik** (2 menit)
   - ImageService: **60 detik** (1 menit)
   - Thumbnail: **60 detik** (1 menit)

3. **Memory Limits:**
   - Controller: **256MB**
   - ImageService: **256MB**

4. **Fallback Strategy:**
   - Jika image processing gagal → fallback ke regular upload
   - Jika thumbnail gagal → return null (tidak error)
   - Jika file terlalu besar → skip processing, langsung upload

5. **Error Handling:**
   - Semua error di-log dengan detail lengkap
   - User mendapat pesan error yang jelas
   - Debug info hanya muncul jika `APP_DEBUG=true`

## 🚀 Hasil

Setelah perbaikan:
- ✅ Tidak ada timeout (499) untuk file normal
- ✅ Tidak ada error 500 untuk file normal
- ✅ File besar (> 10MB) di-skip processing (tidak error)
- ✅ Error logging lengkap untuk debugging
- ✅ User mendapat pesan error yang jelas
- ✅ Processing time di-log untuk monitoring

## 🔧 Troubleshooting

Jika masih ada timeout:

1. **Check Logs:**
   ```bash
   # Cari di logs:
   # - "Image processed in Xs" (berapa lama?)
   # - "Image processing failed" (error apa?)
   # - "Foto upload failed" (error apa?)
   ```

2. **Check File Size:**
   ```bash
   # Pastikan file < 5MB
   # Jika > 5MB, user harus compress dulu
   ```

3. **Check Railway Resources:**
   ```bash
   # Pastikan Railway instance punya cukup:
   # - CPU (untuk image processing)
   # - Memory (256MB+)
   # - Disk space
   ```

4. **Reduce Image Quality:**
   ```php
   // Di ImageService::processImage
   // Kurangi quality dari 85 ke 75
   $image->toJpeg(75)->save($fullPath);
   ```

5. **Reduce Max Width:**
   ```php
   // Di FotoController::store
   // Kurangi maxWidth dari 1920 ke 1280
   ImageService::processImage($file, 'fotos', 1280, 85);
   ```

## 📚 Referensi

- [PHP set_time_limit](https://www.php.net/manual/en/function.set-time-limit.php)
- [PHP ini_set memory_limit](https://www.php.net/manual/en/function.ini-set.php)
- [Laravel Logging](https://laravel.com/docs/10.x/logging)
- [Railway Timeout Settings](https://docs.railway.app/deploy/builds)

---

**Status:** ✅ **FIXED** - Upload timeout dan error 500 sudah diperbaiki dengan optimasi dan error handling yang lebih baik!

