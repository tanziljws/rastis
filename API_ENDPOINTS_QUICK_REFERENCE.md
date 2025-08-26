# API Endpoints Quick Reference - Sekolah Galeri

## Base URL
```
http://localhost:8000/api
```

## Authentication
- **Admin**: Full CRUD access dengan Bearer token
- **Guest**: Read-only access (GET endpoints)

---

## 🔐 Authentication Endpoints

### POST /api/login
**Description:** Login admin dan dapatkan token

**Request:**
```json
{
    "username": "admin",
    "password": "admin123"
}
```

**Response (200):**
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

**Response (200):**
```json
{
    "message": "Logout successful"
}
```

### GET /api/me
**Description:** Dapatkan informasi petugas yang sedang login (Admin only)

**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
{
    "petugas": {
        "id": 1,
        "username": "admin"
    }
}
```

---

## 📰 Posts Endpoints

### GET /api/posts
**Description:** Dapatkan daftar posts (Guest access)

**Query Parameters:**
- `search` - Search dalam judul dan isi
- `kategori_id` - Filter berdasarkan kategori
- `status` - Filter berdasarkan status (published/draft/archived)
- `per_page` - Jumlah item per halaman (default: 15)

**Response (200):**
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
**Description:** Dapatkan detail post (Guest access)

### POST /api/posts
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

### PUT /api/posts/{id}
**Description:** Update post (Admin only)

### DELETE /api/posts/{id}
**Description:** Hapus post (Admin only)

---

## 📂 Kategori Endpoints

### GET /api/kategori
**Description:** Dapatkan daftar kategori (Admin only)

**Headers:** `Authorization: Bearer {token}`

### POST /api/kategori
**Description:** Buat kategori baru (Admin only)

**Headers:** `Authorization: Bearer {token}`

**Request:**
```json
{
    "judul": "Kategori Baru"
}
```

### GET /api/kategori/{id}
**Description:** Dapatkan detail kategori (Admin only)

### PUT /api/kategori/{id}
**Description:** Update kategori (Admin only)

### DELETE /api/kategori/{id}
**Description:** Hapus kategori (Admin only)

---

## 🖼️ Galery Endpoints

### GET /api/galeries
**Description:** Dapatkan daftar galeries (Guest access)

**Query Parameters:**
- `post_id` - Filter berdasarkan post
- `status` - Filter berdasarkan status (active/inactive)
- `per_page` - Jumlah item per halaman

### GET /api/galeries/{id}
**Description:** Dapatkan detail galery (Guest access)

### POST /api/galeries
**Description:** Buat galery baru (Admin only)

**Headers:** `Authorization: Bearer {token}`

**Request:**
```json
{
    "post_id": 1,
    "position": 1,
    "status": "active"
}
```

### PUT /api/galeries/{id}
**Description:** Update galery (Admin only)

### DELETE /api/galeries/{id}
**Description:** Hapus galery (Admin only)

---

## 📸 Foto Endpoints

### GET /api/fotos
**Description:** Dapatkan daftar fotos (Guest access)

**Query Parameters:**
- `galery_id` - Filter berdasarkan galery
- `search` - Search dalam judul dan file
- `per_page` - Jumlah item per halaman

**Response (200):**
```json
{
    "data": [
        {
            "id": 1,
            "file": "foto/1703123456_test_image.jpg",
            "file_url": "http://localhost:8000/storage/foto/1703123456_test_image.jpg",
            "judul": "Kegiatan Belajar Mengajar",
            "galery": {...},
            "created_at": "2024-01-01T00:00:00.000000Z",
            "updated_at": "2024-01-01T00:00:00.000000Z"
        }
    ]
}
```

### GET /api/fotos/{id}
**Description:** Dapatkan detail foto (Guest access)

### POST /api/fotos
**Description:** Upload foto baru (Admin only)

**Headers:** `Authorization: Bearer {token}`

**Request (multipart/form-data):**
```
galery_id: 1
file: [binary file - jpeg,jpg,png,gif,webp, max 5MB]
judul: "Judul Foto" (optional)
```

**Response (201):**
```json
{
    "data": {
        "id": 1,
        "file": "foto/1703123456_test_image.jpg",
        "file_url": "http://localhost:8000/storage/foto/1703123456_test_image.jpg",
        "judul": "Judul Foto",
        "galery": {...},
        "created_at": "2024-12-21T10:30:00.000000Z",
        "updated_at": "2024-12-21T10:30:00.000000Z"
    }
}
```

### PUT /api/fotos/{id}
**Description:** Update foto (Admin only)

**Note:** Jika file baru diupload, file lama akan otomatis dihapus.

### DELETE /api/fotos/{id}
**Description:** Hapus foto dan file fisik (Admin only)

**Note:** Operasi ini akan menghapus record dari database dan file fisik dari storage.

---

## 📋 Profile Endpoints

### GET /api/profiles
**Description:** Dapatkan daftar profiles (Guest access)

**Query Parameters:**
- `search` - Search dalam judul dan isi
- `per_page` - Jumlah item per halaman

### GET /api/profiles/{id}
**Description:** Dapatkan detail profile (Guest access)

### POST /api/profiles
**Description:** Buat profile baru (Admin only)

**Headers:** `Authorization: Bearer {token}`

**Request:**
```json
{
    "judul": "Judul Profile",
    "isi": "Isi profile yang lengkap..."
}
```

### PUT /api/profiles/{id}
**Description:** Update profile (Admin only)

### DELETE /api/profiles/{id}
**Description:** Hapus profile (Admin only)

---

## 📊 Admin Dashboard Endpoints

### GET /api/admin/dashboard
**Description:** Dashboard statistik (Admin only)

**Headers:** `Authorization: Bearer {token}`

**Response (200):**
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

### GET /api/admin/categories
**Description:** Dapatkan semua kategori (Admin only)

### GET /api/admin/posts
**Description:** Dapatkan semua posts dengan relasi (Admin only)

---

## 🔧 Testing Examples

### cURL Examples

#### Login
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"username": "admin", "password": "admin123"}'
```

#### Get Posts (Guest)
```bash
curl -X GET "http://localhost:8000/api/posts?status=published&per_page=5"
```

#### Create Post (Admin)
```bash
curl -X POST http://localhost:8000/api/posts \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "judul": "Post Baru",
    "isi": "Isi post...",
    "status": "published"
  }'
```

#### Upload File (Admin)
```bash
curl -X POST http://localhost:8000/api/fotos \
  -H "Authorization: Bearer {token}" \
  -F "galery_id=1" \
  -F "file=@/path/to/image.jpg" \
  -F "judul=My Photo"
```

#### Delete File (Admin)
```bash
curl -X DELETE http://localhost:8000/api/fotos/1 \
  -H "Authorization: Bearer {token}"
```

---

## 📝 Validation Rules

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
- `file`: required, file, mimes:jpeg,jpg,png,gif,webp, max:5120 (5MB)
- `judul`: nullable, string, max:255

### Profile
- `judul`: required, string, max:255
- `isi`: required, string

---

## 🚨 Error Responses

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

---

## 📚 Documentation Links

- **Interactive Documentation**: http://localhost:8000/docs
- **Postman Collection**: http://localhost:8000/docs.postman
- **OpenAPI Spec**: http://localhost:8000/docs.openapi

---

## ✅ Features

- ✅ **Pagination** - Semua list endpoints mendukung pagination
- ✅ **Search & Filtering** - Search dalam judul dan isi
- ✅ **File Upload** - Multipart file upload dengan validation
- ✅ **File Management** - Automatic file deletion on update/delete
- ✅ **Authentication** - Sanctum token-based authentication
- ✅ **Authorization** - Admin vs Guest access control
- ✅ **Validation** - Request validation untuk semua input
- ✅ **Resource Classes** - JSON response yang rapi dan konsisten
- ✅ **Error Handling** - Proper error responses
- ✅ **Storage Link** - Public access to uploaded files
