# Panduan Upload dan Tampilkan Foto

## Overview
Fitur upload dan tampilkan foto telah diperbaiki sesuai dengan syarat yang diminta. File foto disimpan di `storage/app/public/foto/` dan dapat diakses melalui URL publik.

## Fitur yang Sudah Diperbaiki

### 1. File Controller (FotoController.php)
- **Lokasi**: `app/Http/Controllers/Api/FotoController.php` (API) dan `app/Http/Controllers/FotoController.php` (Web)
- **Validasi file**: jpg, jpeg, png dengan maksimal 2MB
- **Penyimpanan**: File disimpan ke `storage/app/public/foto/`
- **Database**: Kolom `file` menyimpan path lengkap (contoh: "foto/namafile.jpg")

### 2. Blade Views
- **Galeri Public**: `resources/views/galeri/index.blade.php`
- **Admin Panel**: `resources/views/admin/fotos.blade.php`
- **CSS Styling**: Ditambahkan di `resources/css/app.css`

### 3. Routes
- **Public**: `/galeri` - Menampilkan galeri foto
- **Admin**: `/admin/fotos` - Kelola foto (CRUD)

## Cara Penggunaan

### 1. Upload Foto via API
```bash
POST /api/fotos
Content-Type: multipart/form-data

galery_id: 1
file: [file upload]
judul: "Judul Foto (opsional)"
```

### 2. Upload Foto via Web Form
1. Buka `/admin/fotos`
2. Klik "Upload Foto"
3. Pilih galery, file foto, dan judul
4. Klik "Upload Foto"

### 3. Tampilkan Foto di Blade
```php
@if(str_contains($foto->file, '/'))
    {{-- Jika file sudah berisi path lengkap --}}
    <img src="{{ asset('storage/' . $foto->file) }}" alt="{{ $foto->judul }}">
@else
    {{-- Jika file hanya berisi nama file --}}
    <img src="{{ asset('storage/foto/' . $foto->file) }}" alt="{{ $foto->judul }}">
@endif
```

## Konfigurasi Storage

### 1. Pastikan Storage Link Sudah Dibuat
```bash
php artisan storage:link
```

### 2. Struktur Folder
```
storage/
├── app/
│   └── public/
│       └── foto/          # Folder untuk menyimpan foto
│           ├── foto1.jpg
│           ├── foto2.png
│           └── ...
└── ...

public/
└── storage -> ../storage/app/public  # Symlink
```

## CSS Styling

### Gallery Image Container
```css
.gallery-image-container {
    position: relative;
    width: 100%;
    height: 200px;
    overflow: hidden;
    border-radius: 0.5rem 0.5rem 0 0;
}

.gallery-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.gallery-image:hover {
    transform: scale(1.05);
}
```

## Validasi File

### Format yang Diizinkan
- JPEG (.jpg, .jpeg)
- PNG (.png)

### Batasan Ukuran
- Maksimal 2MB per file

### Contoh Validasi Error
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "file": ["The file must be a file of type: jpeg, jpg, png."],
        "file": ["The file may not be greater than 2048 kilobytes."]
    }
}
```

## Testing

### 1. Test Upload Form
Buka `http://localhost:8000/test_upload_form.html` untuk testing upload foto.

### 2. Test Galeri
- **Public**: `http://localhost:8000/galeri`
- **Admin**: `http://localhost:8000/admin/fotos`

## Troubleshooting

### 1. Foto Tidak Muncul
- Pastikan `php artisan storage:link` sudah dijalankan
- Periksa permission folder `storage/app/public/foto/`
- Pastikan file ada di folder yang benar

### 2. Upload Gagal
- Periksa ukuran file (maksimal 2MB)
- Periksa format file (hanya jpg, jpeg, png)
- Periksa permission folder storage

### 3. Error 500
- Periksa log Laravel di `storage/logs/laravel.log`
- Pastikan semua dependency sudah terinstall

## File yang Dimodifikasi

1. `app/Http/Controllers/Api/FotoController.php` - API Controller
2. `app/Http/Controllers/FotoController.php` - Web Controller (baru)
3. `resources/views/galeri/index.blade.php` - Galeri public
4. `resources/views/admin/fotos.blade.php` - Admin panel
5. `resources/css/app.css` - Styling
6. `routes/web.php` - Routes
7. `public/test_upload_form.html` - Test form (baru)

## Kesimpulan

Fitur upload dan tampilkan foto sudah berfungsi dengan baik sesuai syarat:
- ✅ File disimpan ke `storage/app/public/foto/`
- ✅ Database menyimpan path lengkap
- ✅ Validasi file (jpg, jpeg, png, max 2MB)
- ✅ Menggunakan `$file->store('foto', 'public')`
- ✅ Blade view menampilkan gambar dengan `asset('storage/...')`
- ✅ CSS styling untuk galeri (tinggi 200px, object-fit: cover)
- ✅ Storage link sudah dibuat
