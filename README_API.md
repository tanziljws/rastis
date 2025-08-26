# Laravel Sanctum API Authentication

## Setup Complete ✅
- Laravel Sanctum installed and configured
- Personal access tokens table created
- Petugas model with HasApiTokens trait
- Custom middleware for Petugas authentication
- API endpoints protected with Sanctum middleware

## API Endpoints

### Public
- `POST /api/login` - Login with username/password

### Protected (Require Bearer Token)
- `POST /api/logout` - Logout and revoke token
- `GET /api/me` - Get current user info
- `GET /api/admin/dashboard` - Admin dashboard stats
- `GET /api/admin/categories` - Get all categories
- `GET /api/admin/posts` - Get all posts with relations

## Usage Example

```bash
# 1. Login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"username": "admin", "password": "admin123"}'

# 2. Use token for protected endpoints
curl -X GET http://localhost:8000/api/admin/dashboard \
  -H "Authorization: Bearer {token}"

# 3. Logout
curl -X POST http://localhost:8000/api/logout \
  -H "Authorization: Bearer {token}"
```

## Test
Run: `php test_api.php`

## Admin Credentials
- Username: `admin`
- Password: `admin123`
