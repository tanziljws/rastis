# Dokumentasi Perbaikan Upload Foto

## Masalah yang Ditemukan
Aplikasi selalu menampilkan pesan "gagal upload" meskipun file berhasil tersimpan dan masuk database.

## Root Cause
JavaScript di view admin.fotos menggunakan AJAX (fetch) untuk upload, tapi controller mengembalikan redirect HTML, bukan JSON response.

## Perbaikan yang Dilakukan

### 1. FotoController@store Method

**Sebelum:**
```php
if (request()->expectsJson()) {
    return response()->json([...]);
}
return redirect()->route('admin.fotos')->with('success', 'Foto berhasil diupload!');
```

**Sesudah:**
```php
if (request()->expectsJson() || request()->ajax()) {
    return response()->json([...]);
}
return redirect()->route('admin.fotos')->with('success', 'Foto berhasil diupload.');
```

### 2. Error Handling yang Konsisten

**Semua error case sekarang mengembalikan:**
- **AJAX/JSON**: Response JSON dengan `success: false`
- **Form biasa**: Redirect dengan `with('error', 'Gagal upload.')`

### 3. Field Database yang Disimpan

```php
$foto = Foto::create([
    'galery_id' => $validated['galery_id'],    // ✅ Required
    'judul' => $validated['judul'] ?? null,    // ✅ Optional
    'file' => basename($path),                 // ✅ Basename only
]);
```

### 4. Penyimpanan File

```php
$path = $file->store('fotos', 'public');  // ✅ storage/app/public/fotos
```

## Testing

### 1. Test via Admin Panel
- Buka `/admin/fotos`
- Upload foto melalui form
- Harus menampilkan pesan sukses

### 2. Test via Simple Form
- Buka `/test_upload_simple.html`
- Upload foto tanpa AJAX
- Harus redirect dengan pesan sukses

### 3. Test Error Cases
- Upload tanpa file → "Gagal upload."
- Upload file invalid → "Gagal upload."
- Upload tanpa galery_id → "Gagal upload."

## File yang Dimodifikasi

1. `app/Http/Controllers/FotoController.php` - Method store()
2. `public/test_upload_simple.html` - Test form sederhana

## Verifikasi Perbaikan

### ✅ Berhasil Upload
- File tersimpan di `storage/app/public/fotos/`
- Data masuk database dengan field yang benar
- Pesan sukses ditampilkan: "Foto berhasil diupload."

### ✅ Gagal Upload
- Pesan error ditampilkan: "Gagal upload."
- File tidak tersimpan
- Data tidak masuk database

## Struktur File yang Benar

```
storage/app/public/fotos/
├── filename1.jpg
├── filename2.png
└── ...

Database:
- galery_id: 1 (required)
- judul: "Judul Foto" (optional)
- file: "filename1.jpg" (basename only)
```

## Kesimpulan

Bug telah diperbaiki dengan:
1. ✅ Menambahkan `request()->ajax()` check untuk AJAX requests
2. ✅ Menyederhanakan pesan error menjadi "Gagal upload."
3. ✅ Memastikan field database sesuai (galery_id, judul, file)
4. ✅ File disimpan di `storage/app/public/fotos` dengan disk `public`
5. ✅ Tidak mengubah fitur lain selain return message
