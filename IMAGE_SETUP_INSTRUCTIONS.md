# Cara Menambahkan Gambar Sekolah ke Halaman Beranda

## Langkah 1: Siapkan Gambar
1. Pastikan Anda memiliki file gambar sekolah dengan nama `maxresdefault.jpg`
2. Format yang didukung: JPG, JPEG, PNG, GIF, WEBP
3. Ukuran yang disarankan: minimal 1920x1080 pixels untuk tampilan optimal

## Langkah 2: Upload Gambar
1. Buat folder `foto` di dalam `storage/app/public/` jika belum ada:
   ```bash
   mkdir storage/app/public/foto
   ```

2. Copy file gambar ke folder tersebut:
   ```bash
   copy "path\to\maxresdefault.jpg" "storage\app\public\foto\maxresdefault.jpg"
   ```

## Langkah 3: Update Halaman Welcome
Setelah gambar diupload, update file `resources/views/welcome.blade.php`:

### Ganti bagian CSS hero-section:
```css
.hero-section {
    position: relative;
    height: 100vh;
    width: 100%;
    background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), 
                url('/storage/foto/maxresdefault.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: white;
    overflow: hidden;
}
```

### Hapus bagian .hero-section::before (tidak diperlukan lagi):
```css
/* Hapus bagian ini */
.hero-section::before {
    content: '';
    /* ... */
}
```

## Langkah 4: Test Halaman
1. Refresh halaman beranda di browser
2. Gambar sekolah seharusnya muncul sebagai background
3. Teks "Selamat Datang di SMKN 4 Kota Bogor" akan tetap terlihat jelas di atas gambar

## Catatan Penting:
- Gambar akan otomatis di-resize untuk mengisi seluruh layar
- Overlay gelap (rgba(0, 0, 0, 0.5)) memastikan teks tetap terbaca
- Jika gambar tidak muncul, pastikan:
  - File ada di lokasi yang benar
  - Storage link sudah dibuat (`php artisan storage:link`)
  - Permission folder storage sudah benar

## Alternatif: Gunakan Gambar dari URL Eksternal
Jika ingin menggunakan gambar dari internet, ganti URL di CSS:
```css
background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), 
            url('https://example.com/path/to/school-image.jpg');
```

