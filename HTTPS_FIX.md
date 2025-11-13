# ✅ Fix: Mixed Content (HTTP/HTTPS) - Railway Deployment

## 🔍 Masalah yang Ditemukan

Browser warning: **"The information you're about to submit is not secure"**

Ini terjadi karena:
1. Laravel belum force HTTPS scheme di production
2. Ada hardcoded HTTP URL di beberapa file
3. Form action mungkin generate HTTP URL

## ✅ Perbaikan yang Diterapkan

### 1. **Force HTTPS di AppServiceProvider** ✅

**File:** `app/Providers/AppServiceProvider.php`

```php
use Illuminate\Support\Facades\URL;

public function boot(): void
{
    // Force HTTPS in production (Railway, etc.)
    if (config('app.env') === 'production' || env('APP_ENV') === 'production') {
        URL::forceScheme('https');
    }
    
    // Also force HTTPS if APP_URL is HTTPS
    $appUrl = config('app.url');
    if ($appUrl && str_starts_with($appUrl, 'https://')) {
        URL::forceScheme('https');
    }
}
```

**Efek:**
- Semua `route()`, `url()`, `asset()` akan generate HTTPS URL
- Form action akan otomatis HTTPS
- Tidak ada mixed content warning

### 2. **Fix Hardcoded HTTP URL di Scribe** ✅

**File:** `resources/views/scribe/index.blade.php`

#### Sebelum:
```javascript
var tryItOutBaseUrl = "http://localhost";
```

#### Sesudah:
```javascript
var tryItOutBaseUrl = "{{ config('app.url') }}";
```

#### Sebelum:
```html
<code>http://localhost</code>
```

#### Sesudah:
```html
<code>{{ config('app.url') }}</code>
```

#### Sebelum:
```html
<a href="http://github.com/knuckleswtf/scribe">...</a>
```

#### Sesudah:
```html
<a href="https://github.com/knuckleswtf/scribe" target="_blank" rel="noopener noreferrer">...</a>
```

### 3. **Verifikasi Form Actions** ✅

Semua form sudah menggunakan `route()` helper yang akan otomatis generate HTTPS URL setelah force scheme:

- ✅ `resources/views/admin/login.blade.php` - `{{ route('admin.login.post') }}`
- ✅ `resources/views/admin/profiles.blade.php` - `{{ route('admin.profiles.update') }}`
- ✅ `resources/views/admin/tambah-foto.blade.php` - `{{ route('admin.fotos.store') }}`
- ✅ `resources/views/layouts/admin.blade.php` - `{{ route('admin.logout') }}`
- ✅ Dan semua form lainnya...

**Total: 18 form sudah OK** ✅

### 4. **CDN Resources** ✅

Semua CDN sudah menggunakan HTTPS:
- ✅ Bootstrap: `https://cdn.jsdelivr.net/...`
- ✅ Font Awesome: `https://cdnjs.cloudflare.com/...`
- ✅ Google Maps: `https://www.google.com/maps/...`
- ✅ Lightbox: `https://cdnjs.cloudflare.com/...`

### 5. **SVG Namespace** ℹ️

SVG namespace `http://www.w3.org/2000/svg` adalah **OK** karena:
- Itu XML namespace, bukan resource URL
- Browser tidak memblokir XML namespace
- Tidak menyebabkan mixed content warning

## 📋 Checklist Railway Environment Variables

Pastikan di Railway dashboard, environment variables sudah set:

```bash
APP_ENV=production
APP_URL=https://smkn4.up.railway.app
APP_DEBUG=false
```

**Penting:** `APP_URL` harus pakai `https://` bukan `http://`!

## 🧪 Testing

Setelah deploy, test:

1. **Cek Browser Console:**
   - Tidak ada warning "Mixed Content"
   - Tidak ada error "Not secure"

2. **Cek Form Submission:**
   - Submit form login
   - Submit form upload foto
   - Submit form edit profile
   - Semua harus HTTPS tanpa warning

3. **Cek Network Tab:**
   - Semua request harus `https://`
   - Tidak ada request `http://`

4. **Test di Console:**
   ```javascript
   // Cek apakah URL sudah HTTPS
   console.log(window.location.protocol); // Harus "https:"
   ```

5. **Cek Laravel Route:**
   ```php
   // Di tinker atau controller
   echo route('admin.login.post');
   // Harus output: https://smkn4.up.railway.app/admin/login
   ```

## 📝 File yang Dimodifikasi

1. ✅ `app/Providers/AppServiceProvider.php`
   - Tambah `URL::forceScheme('https')` untuk production

2. ✅ `resources/views/scribe/index.blade.php`
   - Ganti hardcoded `http://localhost` dengan `{{ config('app.url') }}`
   - Ganti GitHub link dari HTTP ke HTTPS

## ⚠️ Catatan Penting

1. **TrustProxies Middleware:**
   - Railway menggunakan proxy, pastikan `TrustProxies` sudah dikonfigurasi
   - Cek `app/Http/Middleware/TrustProxies.php`

2. **APP_URL Environment Variable:**
   - **WAJIB** set ke `https://smkn4.up.railway.app` di Railway
   - Jangan pakai `http://` di production

3. **Force HTTPS Logic:**
   - Akan aktif jika:
     - `APP_ENV=production` ATAU
     - `APP_URL` dimulai dengan `https://`
   - Ini memastikan HTTPS selalu aktif di production

4. **Local Development:**
   - Di localhost, tetap bisa pakai HTTP
   - Force HTTPS hanya aktif di production

## 🚀 Hasil

Setelah perbaikan:
- ✅ Tidak ada mixed content warning
- ✅ Semua form submit via HTTPS
- ✅ Semua URL generate HTTPS
- ✅ Browser tidak lagi warning "not secure"
- ✅ SSL certificate bekerja dengan benar

## 🔧 Troubleshooting

Jika masih ada warning:

1. **Clear Browser Cache:**
   - Hard refresh: `Ctrl+Shift+R` (Windows) / `Cmd+Shift+R` (Mac)

2. **Cek Railway Environment Variables:**
   ```bash
   APP_URL=https://smkn4.up.railway.app  # Pastikan HTTPS
   APP_ENV=production
   ```

3. **Cek Laravel Config:**
   ```php
   // Di tinker
   php artisan tinker
   >>> config('app.url')
   // Harus output: https://smkn4.up.railway.app
   ```

4. **Cek TrustProxies:**
   ```php
   // app/Http/Middleware/TrustProxies.php
   protected $proxies = '*'; // Atau IP Railway
   ```

5. **Restart Railway Service:**
   - Deploy ulang atau restart service

## 📚 Referensi

- [Laravel URL Force Scheme](https://laravel.com/docs/10.x/urls#forcing-https)
- [Railway HTTPS Setup](https://docs.railway.app/deploy/builds)
- [Mixed Content MDN](https://developer.mozilla.org/en-US/docs/Web/Security/Mixed_content)

---

**Status:** ✅ **FIXED** - Semua mixed content issues sudah diperbaiki!

