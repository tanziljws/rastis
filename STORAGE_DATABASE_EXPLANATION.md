# Penjelasan Storage dan Database

## 📊 Bagaimana Foto Disimpan?

### 1. **Database MySQL (Railway)**
Database menyimpan **metadata** foto, BUKAN file fisik:
- ✅ `id` - ID unik foto
- ✅ `file` - **Path/nama file** (contoh: `fotos/abc123.jpg`)
- ✅ `judul` - Judul foto
- ✅ `kategori_id` - ID kategori
- ✅ `galery_id` - ID galeri
- ✅ `thumbnail` - Path thumbnail
- ✅ `created_at`, `updated_at` - Timestamp

**Data ini PERSISTENT** - tidak hilang saat container restart.

### 2. **File Storage (Railway Ephemeral)**
File fisik disimpan di:
- `storage/app/public/fotos/` - File original
- `storage/app/public/fotos/thumbnails/` - Thumbnail

**File ini TIDAK PERSISTENT** - hilang saat container restart karena Railway menggunakan ephemeral storage.

## ⚠️ Masalah yang Terjadi

### Skenario:
1. Upload foto → File disimpan di `storage/app/public/fotos/abc123.jpg`
2. Database menyimpan path: `fotos/abc123.jpg`
3. Container restart → File fisik **HILANG**
4. Database masih ada record dengan path `fotos/abc123.jpg`
5. Browser coba load → **403 Forbidden** karena file tidak ada

### Kenapa 403?
- File tidak ada di storage (hilang saat restart)
- Web server tidak bisa menemukan file
- Browser menampilkan error 403

## ✅ Solusi

### 1. **Railway Volume (WAJIB!)**
Setup persistent storage:

1. Buka Railway Dashboard
2. Pilih service
3. Klik "Volumes"
4. Create volume:
   - **Name**: `storage-volume`
   - **Mount Path**: `/app/storage/app/public`
   - **Size**: 1GB+ (sesuai kebutuhan)

Setelah ini, file akan **PERSISTENT** dan tidak hilang saat restart.

### 2. **Cloud Storage (Alternatif)**
Gunakan S3/Cloudinary untuk storage external:
- File disimpan di cloud
- Tidak terpengaruh container restart
- Lebih reliable untuk production

## 🔍 Verifikasi

### Cek Database:
```sql
SELECT id, file, judul FROM foto LIMIT 10;
```
Record masih ada? ✅ Database OK

### Cek File:
```bash
ls -la storage/app/public/fotos/
```
File ada? ❌ File hilang (ephemeral storage)

## 📝 Kesimpulan

- ✅ **Database MySQL**: Data foto (path, judul, dll) **PERSISTENT**
- ❌ **File Storage**: File fisik **TIDAK PERSISTENT** (tanpa volume)
- ✅ **Solusi**: Setup Railway Volume untuk persistent storage

**File fisik TIDAK disimpan di database** - hanya path-nya yang disimpan. Ini adalah praktik standar untuk efisiensi database.

