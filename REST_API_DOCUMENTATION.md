# REST API Documentation - Sekolah Galeri

## Overview
REST API untuk sistem galeri sekolah dengan autentikasi Laravel Sanctum. API ini menyediakan endpoint untuk manajemen kategori, posts, galery, foto, dan profile.

## Authentication
- **Admin**: Full CRUD access dengan Bearer token
- **Guest**: Read-only access (GET endpoints)

## Base URL
```
http://localhost:8000/api
```

## Authentication Endpoints

### POST /api/login
**Description:** Login admin dan dapatkan token

**Request:**
```json
{
    "username": "admin",
    "password": "admin123"
}
```

**Response:**
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

### POST /api/logout
**Description:** Logout dan revoke token (Admin only)

**Headers:** `Authorization: Bearer {token}`

**Response:**
```json
{
    "message": "Logout successful"
}
```

## Guest Endpoints (Read-only)

### GET /api/posts
**Description:** Dapatkan daftar posts dengan pagination

**Query Parameters:**
- `search` - Search dalam judul dan isi
- `kategori_id` - Filter berdasarkan kategori
- `status` - Filter berdasarkan status (published/draft/archived)
- `per_page` - Jumlah item per halaman (default: 15)

**Response:**
```json
{
    "data": [
        {
            "id": 1,
            "judul": "Pembukaan Tahun Ajaran Baru",
            "isi": "Selamat datang di tahun ajaran baru...",
            "status": "published",
            "kategori": {
                "id": 1,
                "judul": "Informasi Terkini"
            },
            "petugas": {
                "id": 1,
                "username": "admin"
            },
            "galeries_count": 3,
            "created_at": "2024-01-01T00:00:00.000000Z",
            "updated_at": "2024-01-01T00:00:00.000000Z"
        }
    ],
    "links": {...},
    "meta": {...}
}
```

### GET /api/posts/{id}
**Description:** Dapatkan detail post

**Response:**
```json
{
    "data": {
        "id": 1,
        "judul": "Pembukaan Tahun Ajaran Baru",
        "isi": "Selamat datang di tahun ajaran baru...",
        "status": "published",
        "kategori": {...},
        "petugas": {...},
        "galeries_count": 3,
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

### GET /api/galeries
**Description:** Dapatkan daftar galeries

**Query Parameters:**
- `post_id` - Filter berdasarkan post
- `status` - Filter berdasarkan status (active/inactive)
- `per_page` - Jumlah item per halaman

**Response:**
```json
{
    "data": [
        {
            "id": 1,
            "position": 1,
            "status": "active",
            "post": {...},
            "fotos_count": 5,
            "created_at": "2024-01-01T00:00:00.000000Z",
            "updated_at": "2024-01-01T00:00:00.000000Z"
        }
    ]
}
```

### GET /api/galeries/{id}
**Description:** Dapatkan detail galery

### GET /api/fotos
**Description:** Dapatkan daftar fotos

**Query Parameters:**
- `galery_id` - Filter berdasarkan galery
- `search` - Search dalam judul dan file
- `per_page` - Jumlah item per halaman

**Response:**
```json
{
    "data": [
        {
            "id": 1,
            "file": "kegiatan_belajar_1.jpg",
            "judul": "Kegiatan Belajar Mengajar",
            "galery": {...},
            "created_at": "2024-01-01T00:00:00.000000Z",
            "updated_at": "2024-01-01T00:00:00.000000Z"
        }
    ]
}
```

### GET /api/fotos/{id}
**Description:** Dapatkan detail foto

### GET /api/profiles
**Description:** Dapatkan daftar profiles

**Query Parameters:**
- `search` - Search dalam judul dan isi
- `per_page` - Jumlah item per halaman

**Response:**
```json
{
    "data": [
        {
            "id": 1,
            "judul": "Visi Sekolah",
            "isi": "Menjadi sekolah unggulan...",
            "created_at": "2024-01-01T00:00:00.000000Z",
            "updated_at": "2024-01-01T00:00:00.000000Z"
        }
    ]
}
```

### GET /api/profiles/{id}
**Description:** Dapatkan detail profile

## Admin Endpoints (Full CRUD)

### Kategori Management

#### GET /api/kategori
**Description:** Dapatkan daftar kategori (Admin only)

**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**
- `search` - Search dalam judul
- `per_page` - Jumlah item per halaman

#### POST /api/kategori
**Description:** Buat kategori baru (Admin only)

**Headers:** `Authorization: Bearer {token}`

**Request:**
```json
{
    "judul": "Kategori Baru"
}
```

#### GET /api/kategori/{id}
**Description:** Dapatkan detail kategori (Admin only)

#### PUT /api/kategori/{id}
**Description:** Update kategori (Admin only)

**Request:**
```json
{
    "judul": "Kategori Updated"
}
```

#### DELETE /api/kategori/{id}
**Description:** Hapus kategori (Admin only)

### Posts Management

#### POST /api/posts
**Description:** Buat post baru (Admin only)

**Headers:** `Authorization: Bearer {token}`

**Request:**
```json
{
    "judul": "Judul Post Baru",
    "kategori_id": 1,
    "isi": "Isi post yang lengkap...",
    "status": "published"
}
```

#### PUT /api/posts/{id}
**Description:** Update post (Admin only)

#### DELETE /api/posts/{id}
**Description:** Hapus post (Admin only)

### Galery Management

#### POST /api/galeries
**Description:** Buat galery baru (Admin only)

**Request:**
```json
{
    "post_id": 1,
    "position": 1,
    "status": "active"
}
```

#### PUT /api/galeries/{id}
**Description:** Update galery (Admin only)

#### DELETE /api/galeries/{id}
**Description:** Hapus galery (Admin only)

### Foto Management

#### POST /api/fotos
**Description:** Buat foto baru dengan upload file (Admin only)

**Headers:** `Authorization: Bearer {token}`

**Request (multipart/form-data):**
```
galery_id: 1
file: [binary file - jpeg,jpg,png,gif,webp]
judul: "Judul Foto" (optional)
```

**Validation Rules:**
- `galery_id`: required, exists:galery,id
- `file`: required, file, mimes:jpeg,jpg,png,gif,webp
- `judul`: nullable, string, max:255

**Response:**
```json
{
    "data": {
        "id": 1,
        "file": "foto/1703123456_test_image.jpg",
        "file_url": "http://localhost:8000/storage/foto/1703123456_test_image.jpg",
        "judul": "Judul Foto",
        "galery": {...},
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

#### PUT /api/fotos/{id}
**Description:** Update foto (Admin only)

**Headers:** `Authorization: Bearer {token}`

**Request (multipart/form-data):**
```
galery_id: 1
file: [binary file - jpeg,jpg,png,gif,webp] (optional)
judul: "Judul Foto Updated" (optional)
```

**Note:** Jika file baru diupload, file lama akan otomatis dihapus.

#### DELETE /api/fotos/{id}
**Description:** Hapus foto dan file fisik (Admin only)

**Headers:** `Authorization: Bearer {token}`

**Note:** Operasi ini akan menghapus record dari database dan file fisik dari storage.

### Profile Management

#### POST /api/profiles
**Description:** Buat profile baru (Admin only)

**Request:**
```json
{
    "judul": "Judul Profile",
    "isi": "Isi profile yang lengkap..."
}
```

#### PUT /api/profiles/{id}
**Description:** Update profile (Admin only)

#### DELETE /api/profiles/{id}
**Description:** Hapus profile (Admin only)

## Admin Dashboard Endpoints

### GET /api/admin/dashboard
**Description:** Dashboard statistik (Admin only)

**Response:**
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

## Validation Rules

### Kategori
- `judul`: required, string, max:255, unique

### Post
- `judul`: required, string, max:255
- `kategori_id`: nullable, exists:kategori,id
- `isi`: required, string
- `status`: required, in:published,draft,archived

### Galery
- `post_id`: required, exists:posts,id
- `position`: required, integer, min:1
- `status`: required, in:active,inactive

### Foto
- `galery_id`: required, exists:galery,id
- `file`: required, file, mimes:jpeg,jpg,png,gif,webp
- `judul`: nullable, string, max:255

### Profile
- `judul`: required, string, max:255
- `isi`: required, string

## Error Responses

### Validation Error (422)
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "judul": ["The judul field is required."]
    }
}
```

### Unauthorized (401)
```json
{
    "message": "Unauthenticated."
}
```

### Not Found (404)
```json
{
    "message": "No query results for model [App\\Models\\Post] 999"
}
```

## Usage Examples

### Guest Access
```bash
# Get all published posts
curl -X GET "http://localhost:8000/api/posts?status=published"

# Get specific post
curl -X GET "http://localhost:8000/api/posts/1"

# Search posts
curl -X GET "http://localhost:8000/api/posts?search=sekolah"
```

### Admin Access
```bash
# Login first
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"username": "admin", "password": "admin123"}'

# Use token for admin operations
curl -X POST http://localhost:8000/api/posts \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "judul": "Post Baru",
    "isi": "Isi post...",
    "status": "published"
  }'

# Upload file
curl -X POST http://localhost:8000/api/fotos \
  -H "Authorization: Bearer {token}" \
  -F "galery_id=1" \
  -F "file=@/path/to/image.jpg" \
  -F "judul=My Photo"
```

## Features

✅ **Pagination** - Semua list endpoints mendukung pagination  
✅ **Search** - Search dalam judul dan isi  
✅ **Filtering** - Filter berdasarkan kategori, status, dll  
✅ **Validation** - Request validation untuk semua input  
✅ **Resource Classes** - JSON response yang rapi dan konsisten  
✅ **Authentication** - Sanctum token-based authentication  
✅ **Authorization** - Admin vs Guest access control  
✅ **Relationships** - Load relationships saat diperlukan  
✅ **Counts** - Include counts untuk related models  
✅ **Error Handling** - Proper error responses  
✅ **File Upload** - Multipart file upload with validation  
✅ **File Management** - Automatic file deletion on update/delete  
✅ **Storage Link** - Public access to uploaded files
