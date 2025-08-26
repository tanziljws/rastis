# API Documentation - Laravel Sanctum Authentication

## Overview
Laravel Sanctum telah dikonfigurasi untuk autentikasi API berbasis token pada project sekolah-galeri. Sistem menggunakan model `Petugas` untuk autentikasi.

## Setup yang Telah Dilakukan

### 1. Instalasi dan Konfigurasi
- ✅ Laravel Sanctum terinstall (`laravel/sanctum: ^4.2`)
- ✅ Konfigurasi Sanctum dipublish (`config/sanctum.php`)
- ✅ Migration `personal_access_tokens` terbuat dan dijalankan
- ✅ Model `Petugas` menggunakan trait `HasApiTokens`
- ✅ Custom middleware `SanctumPetugasGuard` untuk handle Petugas model
- ✅ API routes dikonfigurasi dengan middleware protection

### 2. Database
- ✅ Tabel `personal_access_tokens` tersedia
- ✅ User admin: `username: admin`, `password: admin123`
- ✅ Sample data dari seeders tersedia

## API Endpoints

### Public Endpoints

#### POST /api/login
**Description:** Login petugas dan dapatkan token

**Request Body:**
```json
{
    "username": "admin",
    "password": "admin123"
}
```

**Response Success (200):**
```json
{
    "message": "Login successful",
    "token": "1|abc123def456...",
    "petugas": {
        "id": 1,
        "username": "admin"
    }
}
```

**Response Error (422):**
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "username": ["The provided credentials are incorrect."]
    }
}
```

### Protected Endpoints (Require Bearer Token)

#### POST /api/logout
**Description:** Logout dan revoke token

**Headers:**
```
Authorization: Bearer {token}
```

**Response Success (200):**
```json
{
    "message": "Logout successful"
}
```

#### GET /api/me
**Description:** Dapatkan informasi petugas yang sedang login

**Headers:**
```
Authorization: Bearer {token}
```

**Response Success (200):**
```json
{
    "petugas": {
        "id": 1,
        "username": "admin"
    }
}
```

### Admin Endpoints (Require Bearer Token)

#### GET /api/admin/dashboard
**Description:** Dashboard admin dengan statistik

**Headers:**
```
Authorization: Bearer {token}
```

**Response Success (200):**
```json
{
    "message": "Admin dashboard accessed successfully",
    "stats": {
        "total_kategori": 3,
        "total_posts": 5,
        "total_galeries": 12,
        "total_fotos": 48,
        "total_profiles": 3
    }
}
```

#### GET /api/admin/categories
**Description:** Dapatkan semua kategori

**Headers:**
```
Authorization: Bearer {token}
```

**Response Success (200):**
```json
{
    "categories": [
        {
            "id": 1,
            "judul": "Informasi Terkini",
            "created_at": "2024-01-01T00:00:00.000000Z",
            "updated_at": "2024-01-01T00:00:00.000000Z"
        }
    ]
}
```

#### GET /api/admin/posts
**Description:** Dapatkan semua posts dengan relasi

**Headers:**
```
Authorization: Bearer {token}
```

**Response Success (200):**
```json
{
    "posts": [
        {
            "id": 1,
            "judul": "Pembukaan Tahun Ajaran Baru 2024/2025",
            "kategori_id": 1,
            "isi": "Selamat datang di tahun ajaran baru...",
            "petugas_id": 1,
            "status": "published",
            "created_at": "2024-01-01T00:00:00.000000Z",
            "updated_at": "2024-01-01T00:00:00.000000Z",
            "kategori": {
                "id": 1,
                "judul": "Informasi Terkini"
            },
            "petugas": {
                "id": 1,
                "username": "admin"
            }
        }
    ]
}
```

## Cara Penggunaan

### 1. Login
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "username": "admin",
    "password": "admin123"
  }'
```

### 2. Akses Protected Endpoint
```bash
curl -X GET http://localhost:8000/api/admin/dashboard \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

### 3. Logout
```bash
curl -X POST http://localhost:8000/api/logout \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

## Testing

### Jalankan Test Script
```bash
php test_api.php
```

### Manual Testing dengan Postman/Insomnia
1. Import collection dengan endpoint di atas
2. Set base URL: `http://localhost:8000/api`
3. Login untuk dapat token
4. Set Authorization header dengan Bearer token
5. Test semua protected endpoints

## Keamanan

### Token Management
- ✅ Token otomatis di-revoke saat login baru
- ✅ Token di-revoke saat logout
- ✅ Token menggunakan prefix untuk security scanning
- ✅ Token tidak memiliki expiration (bisa dikonfigurasi di `config/sanctum.php`)

### Middleware Protection
- ✅ Semua admin routes dilindungi dengan `auth:sanctum`
- ✅ Custom middleware untuk handle Petugas model
- ✅ CSRF protection untuk web routes
- ✅ Proper error handling

## Konfigurasi Tambahan

### Token Expiration
Untuk set token expiration, edit `config/sanctum.php`:
```php
'expiration' => 60 * 24 * 7, // 7 days
```

### Token Prefix
Untuk set token prefix:
```php
'token_prefix' => env('SANCTUM_TOKEN_PREFIX', 'sk_'),
```

### Stateful Domains
Untuk SPA authentication, tambahkan domain di `config/sanctum.php`:
```php
'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
    '%s%s',
    'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1',
    env('APP_URL') ? ','.parse_url(env('APP_URL'), PHP_URL_HOST) : '',
))),
```

## Troubleshooting

### Error: "Unauthenticated"
- Pastikan token valid dan tidak expired
- Pastikan Authorization header format: `Bearer {token}`
- Pastikan token belum di-revoke

### Error: "Model not found"
- Pastikan Petugas model menggunakan trait `HasApiTokens`
- Pastikan middleware `sanctum.petugas` terdaftar

### Error: "Token not found"
- Pastikan token dikirim dengan format yang benar
- Pastikan token belum di-revoke saat logout

## File yang Terlibat

### Controllers
- `app/Http/Controllers/Api/AuthController.php`
- `app/Http/Controllers/Api/AdminController.php`

### Models
- `app/Models/Petugas.php` (dengan HasApiTokens trait)

### Middleware
- `app/Http/Middleware/SanctumPetugasGuard.php`

### Routes
- `routes/api.php`

### Config
- `config/sanctum.php`

### Migrations
- `database/migrations/2025_08_26_012638_create_personal_access_tokens_table.php`

### Test
- `test_api.php`
