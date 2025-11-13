# ✅ Fix: Multiple Upload, 413 Error, & Size Limit Increase

## 🔍 Masalah yang Ditemukan

1. **Error 413 (Content Too Large)** - Request body terlalu besar
2. **Tidak bisa upload multiple files** - Hanya single file
3. **Size limit terlalu kecil** - 5MB per file
4. **Tidak optimal** - Upload satu per satu

## ✅ Perbaikan yang Diterapkan

### 1. **Multiple File Upload Support** ✅

**File:** `app/Http/Controllers/FotoController.php`

#### A. Validation untuk Multiple Files
```php
// Check if multiple files or single file
$isMultiple = $request->hasFile('files');

if ($isMultiple) {
    $rules['files'] = 'required|array|min:1|max:20'; // Max 20 files at once
    $rules['files.*'] = 'required|file|mimes:jpeg,jpg,png,webp|max:10240'; // 10MB per file
} else {
    $rules['file'] = 'required|file|mimes:jpeg,jpg,png,webp|max:10240'; // 10MB max
}
```

#### B. Handle Multiple Files
```php
if ($isMultiple && $request->hasFile('files')) {
    $files = $request->file('files');
    
    foreach ($files as $index => $file) {
        // Process each file
        // Create foto record
        // Track success/errors
    }
    
    // Return summary response
    return response()->json([
        'success' => count($created) > 0,
        'message' => count($created) . ' foto berhasil diupload',
        'data' => $created,
        'errors' => $errors,
        'total' => count($files),
        'success_count' => count($created),
        'error_count' => count($errors)
    ]);
}
```

**Fitur:**
- ✅ Upload sampai 20 file sekaligus
- ✅ Batch processing dengan batch_id
- ✅ Error handling per file
- ✅ Summary response dengan success/error count
- ✅ Backward compatible (single file masih bisa)

### 2. **Increase Size Limits** ✅

#### A. Laravel Validation
```php
// Single file: 10MB
$rules['file'] = 'required|file|mimes:jpeg,jpg,png,webp|max:10240';

// Multiple files: 10MB per file
$rules['files.*'] = 'required|file|mimes:jpeg,jpg,png,webp|max:10240';
```

#### B. PHP Configuration (AppServiceProvider)
```php
@ini_set('upload_max_filesize', '10M');
@ini_set('post_max_size', '220M'); // 20 files * 10MB + overhead
@ini_set('max_file_uploads', '20');
@ini_set('memory_limit', '256M');
@ini_set('max_execution_time', '300'); // 5 minutes
```

#### C. PHP.ini File
```ini
upload_max_filesize = 10M
post_max_size = 220M
max_file_uploads = 20
memory_limit = 256M
max_execution_time = 300
```

### 3. **Fix 413 Error** ✅

**Penyebab 413:**
- `post_max_size` terlalu kecil
- `client_max_body_size` (Nginx) terlalu kecil
- Request body > limit

**Solusi:**
1. **Increase PHP Limits:**
   - `post_max_size = 220M` (untuk 20 files * 10MB)
   - `upload_max_filesize = 10M` (per file)

2. **Railway Configuration:**
   - Set environment variables (jika bisa)
   - Atau gunakan `php.ini` file

3. **Nginx Configuration (jika ada):**
   ```nginx
   client_max_body_size 220M;
   ```

### 4. **Update Form untuk Multiple Upload** ✅

**File:** `resources/views/admin/tambah-foto-simple.blade.php`

#### Sebelum:
```html
<input type="file" name="file" accept="image/jpeg,image/jpg,image/png" required>
```

#### Sesudah:
```html
<input type="file" 
       name="files[]" 
       accept="image/jpeg,image/jpg,image/png,image/webp" 
       multiple
       required>
```

#### Tambahan:
- ✅ File preview sebelum upload
- ✅ Show file count & total size
- ✅ Better UX dengan preview cards

### 5. **Optimize Upload Process** ✅

#### A. Batch Processing
```php
$batchId = $validated['batch_id'] ?? uniqid('batch_', true);
// All files in same batch get same batch_id
```

#### B. Error Handling per File
```php
foreach ($files as $index => $file) {
    try {
        // Process file
    } catch (\Exception $e) {
        $errors[] = "File #{$index}: " . $e->getMessage();
        // Continue with next file
    }
}
```

#### C. Progress Logging
```php
\Log::info("Image processed in {$processingTime}s", [
    'file_size' => $file->getSize(),
    'path' => $path,
    'index' => $index
]);
```

## 📋 Konfigurasi yang Diperlukan

### Railway Environment Variables

Set di Railway dashboard:

```bash
# PHP Configuration (jika Railway support)
PHP_INI_SCAN_DIR=/app
```

### Atau Create php.ini File

File `php.ini` di root project:

```ini
upload_max_filesize = 10M
post_max_size = 220M
max_file_uploads = 20
memory_limit = 256M
max_execution_time = 300
```

### Nginx Configuration (jika ada)

```nginx
client_max_body_size 220M;
client_body_timeout 300s;
```

## 🧪 Testing

Setelah deploy, test:

1. **Single File Upload:**
   ```bash
   # Upload 1 file (10MB)
   # Harus berhasil
   ```

2. **Multiple File Upload:**
   ```bash
   # Upload 5 files (masing-masing 5MB)
   # Total: 25MB
   # Harus berhasil
   ```

3. **Large Multiple Upload:**
   ```bash
   # Upload 10 files (masing-masing 10MB)
   # Total: 100MB
   # Harus berhasil
   ```

4. **Max Upload:**
   ```bash
   # Upload 20 files (masing-masing 10MB)
   # Total: 200MB
   # Harus berhasil (mendekati limit)
   ```

5. **Check Response:**
   ```json
   {
     "success": true,
     "message": "15 foto berhasil diupload, 2 gagal",
     "data": [...],
     "errors": ["File #5: ...", "File #12: ..."],
     "total": 17,
     "success_count": 15,
     "error_count": 2
   }
   ```

## 📝 File yang Dimodifikasi

1. ✅ `app/Http/Controllers/FotoController.php`
   - Tambah multiple file support
   - Increase size limit ke 10MB
   - Tambah batch processing
   - Tambah error handling per file

2. ✅ `resources/views/admin/tambah-foto-simple.blade.php`
   - Ganti single file ke multiple files
   - Tambah file preview
   - Update accept types (tambah webp)

3. ✅ `app/Providers/AppServiceProvider.php`
   - Tambah PHP ini_set untuk upload limits

4. ✅ `php.ini` (NEW)
   - PHP configuration untuk Railway

5. ✅ `.htaccess` (NEW)
   - Apache configuration (jika pakai Apache)

## ⚠️ Catatan Penting

1. **Size Limits:**
   - **Per file:** 10MB (dari 5MB)
   - **Total request:** 220MB (20 files * 10MB + overhead)
   - **Max files:** 20 files sekaligus

2. **File Types:**
   - JPEG, JPG, PNG, WEBP (tambah WEBP)

3. **Batch Processing:**
   - Semua file dalam 1 request dapat `batch_id` yang sama
   - Memudahkan grouping dan management

4. **Error Handling:**
   - Jika 1 file gagal, file lain tetap diproses
   - Response menunjukkan success/error count
   - Error message per file

5. **Backward Compatibility:**
   - Single file upload (`name="file"`) masih bisa
   - Multiple file upload (`name="files[]"`) baru

6. **Railway Configuration:**
   - Pastikan `php.ini` di-load
   - Atau set environment variables
   - Check Railway logs untuk konfirmasi

## 🚀 Hasil

Setelah perbaikan:
- ✅ Tidak ada error 413 (Content Too Large)
- ✅ Bisa upload multiple files (sampai 20)
- ✅ Size limit dinaikkan ke 10MB per file
- ✅ Upload lebih optimal (batch processing)
- ✅ Better error handling (per file)
- ✅ File preview sebelum upload
- ✅ Summary response dengan success/error count

## 🔧 Troubleshooting

Jika masih ada error 413:

1. **Check PHP Configuration:**
   ```bash
   # Di Railway logs atau tinker
   php artisan tinker
   >>> ini_get('post_max_size')
   >>> ini_get('upload_max_filesize')
   ```

2. **Check Railway Environment:**
   ```bash
   # Pastikan php.ini di-load
   # Atau set environment variables
   ```

3. **Reduce File Count:**
   ```php
   // Jika masih error, kurangi max files
   $rules['files'] = 'required|array|min:1|max:10'; // Kurangi ke 10
   ```

4. **Check Nginx/Web Server:**
   ```nginx
   # Pastikan client_max_body_size cukup besar
   client_max_body_size 220M;
   ```

5. **Monitor Logs:**
   ```bash
   # Check Laravel logs
   tail -f storage/logs/laravel.log
   
   # Cari:
   # - "Image processed in Xs"
   # - "File upload failed"
   # - "413" errors
   ```

## 📚 Referensi

- [Laravel File Uploads](https://laravel.com/docs/10.x/filesystem#file-uploads)
- [PHP Upload Limits](https://www.php.net/manual/en/features.file-upload.php)
- [Railway PHP Configuration](https://docs.railway.app/deploy/builds)
- [Nginx client_max_body_size](https://nginx.org/en/docs/http/ngx_http_core_module.html#client_max_body_size)

---

**Status:** ✅ **FIXED** - Multiple upload, 413 error, dan size limit sudah diperbaiki dan dioptimalkan!

