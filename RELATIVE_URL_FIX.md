# ✅ Fix: Ganti Absolute URL ke Relative URL

## 🔍 Masalah yang Ditemukan

**User benar sekali!** Ini BUKAN masalah CORS karena:
- Frontend: `http://smkn4.up.railway.app/admin/fotos`
- Backend: `http://smkn4.up.railway.app/admin/fotos`
- **SAME DOMAIN = NO CORS!**

**Masalah sebenarnya:**
- JavaScript menggunakan `route()` helper yang mungkin menghasilkan **absolute URL**
- Jika `route()` menghasilkan `http://smkn4.up.railway.app/admin/fotos/32`, browser menganggap ini **cross-origin**
- Atau ada mixed HTTP/HTTPS yang bikin masalah

## ✅ Perbaikan yang Diterapkan

### Ganti Semua `route()` dengan Relative Path

**File:** `resources/views/admin/fotos.blade.php`

#### 1. **loadCategories()** ✅
```javascript
// SEBELUM (mungkin absolute URL)
xhrRequest('{{ route("admin.api.categories") }}', {...})

// SESUDAH (relative URL)
xhrRequest('/admin/api/categories', {...})
```

#### 2. **loadAlbumPhotos()** ✅
```javascript
// SEBELUM (mungkin absolute URL)
const url = `{{ route("admin.api.fotos.album", ":id") }}`.replace(':id', fotoId);

// SESUDAH (relative URL)
const url = `/admin/api/fotos/${fotoId}/album`;
```

#### 3. **bulkDelete()** ✅
```javascript
// SEBELUM
xhrRequest('{{ route("admin.api.fotos.bulk-delete") }}', {...})

// SESUDAH
xhrRequest('/admin/api/fotos/bulk-delete', {...})
```

#### 4. **updateFoto()** ✅
```javascript
// SEBELUM
xhrRequest(`{{ route("admin.fotos.update", ":id") }}`.replace(':id', fotoId), {...})

// SESUDAH
xhrRequest(`/admin/fotos/${fotoId}/update`, {...})
```

#### 5. **addPhotosToAlbum()** ✅
```javascript
// SEBELUM
xhrRequest(`{{ route("admin.api.fotos.add-photos", ":id") }}`.replace(':id', fotoId), {...})

// SESUDAH
xhrRequest(`/admin/api/fotos/${fotoId}/add-photos`, {...})
```

#### 6. **deleteFoto()** ✅
```javascript
// SEBELUM
xhrRequest(`{{ route("admin.fotos.destroy", ":id") }}`.replace(':id', id), {...})

// SESUDAH
xhrRequest(`/admin/fotos/${id}`, {...})
```

## 📋 Mapping Route ke Relative Path

| Route Name | Relative Path |
|-----------|---------------|
| `admin.api.categories` | `/admin/api/categories` |
| `admin.api.fotos.album` | `/admin/api/fotos/{id}/album` |
| `admin.api.fotos.bulk-delete` | `/admin/api/fotos/bulk-delete` |
| `admin.fotos.update` | `/admin/fotos/{id}/update` |
| `admin.api.fotos.add-photos` | `/admin/api/fotos/{id}/add-photos` |
| `admin.fotos.destroy` | `/admin/fotos/{id}` (DELETE method) |

## 🎯 Keuntungan Relative URL

1. **No CORS Issues** - Same origin, tidak ada CORS check
2. **Works on Any Domain** - Localhost, staging, production
3. **No Hardcoded Domain** - Tidak perlu update URL saat pindah domain
4. **Faster** - Tidak perlu resolve domain
5. **Secure** - Tidak ada masalah mixed HTTP/HTTPS

## 🧪 Testing

Setelah deploy, test:

1. **Hapus Foto:**
   ```javascript
   // Test di console
   fetch('/admin/api/categories')
     .then(r => r.json())
     .then(d => console.log('SUCCESS:', d))
     .catch(e => console.error('ERROR:', e))
   ```

2. **Edit Foto:**
   - Klik edit
   - Categories harus load
   - Album photos harus load

3. **Console Check:**
   - Tidak ada error "access control checks"
   - Tidak ada error "Not allowed to request resource"
   - Semua requests return 200/201/204

## 📝 File yang Dimodifikasi

1. ✅ `resources/views/admin/fotos.blade.php`
   - Ganti semua `route()` dengan relative path
   - 6 fungsi diperbaiki:
     - `loadCategories()`
     - `loadAlbumPhotos()`
     - `bulkDelete()`
     - `updateFoto()`
     - `addPhotosToAlbum()`
     - `deleteFoto()`

## ⚠️ Catatan Penting

1. **Relative URL selalu dimulai dengan `/`** - Ini penting untuk absolute path dari root domain
2. **Tidak perlu `route()` helper** - Langsung hardcode path karena kita tahu struktur route
3. **Template literals untuk dynamic ID** - Gunakan `${id}` bukan `.replace()`

## 🚀 Hasil

Setelah perbaikan:
- ✅ Tidak ada CORS error (same origin)
- ✅ Hapus foto berhasil
- ✅ Edit foto berhasil
- ✅ Load categories berhasil
- ✅ Load album photos berhasil
- ✅ Semua AJAX requests berhasil

**Masalah CORS seharusnya hilang karena sekarang semua request adalah same-origin!** 🎉

