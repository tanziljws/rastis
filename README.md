# Sekolah Galeri - School Gallery Management System

A comprehensive Laravel-based school gallery management system with API and web interface for managing school information, posts, galleries, and photos.

## 🚀 Features

### Guest Side (Public)
- **Homepage**: Display school profile and photo gallery
- **Photo Gallery**: Browse school photos organized by galleries
- **School Information**: View school profile and details

### Admin Side (Protected)
- **Authentication**: Secure login system with Laravel Sanctum
- **Dashboard**: Overview with statistics and recent activities
- **Content Management**:
  - Categories management (CRUD)
  - Posts/Articles management (CRUD)
  - Galleries management (CRUD)
  - Photos management with file upload (CRUD)
  - School profile management (CRUD)

### API Features
- **RESTful API**: Complete CRUD operations for all entities
- **Authentication**: Token-based authentication with Laravel Sanctum
- **File Upload**: Secure file upload for photos with validation
- **Pagination**: Efficient data pagination for large datasets
- **Filtering & Search**: Advanced filtering and search capabilities
- **API Documentation**: Auto-generated documentation with Laravel Scribe

## 🛠️ Tech Stack

- **Backend**: Laravel 11.x
- **Database**: MySQL
- **Authentication**: Laravel Sanctum
- **Frontend**: Laravel Blade + Bootstrap 5
- **File Storage**: Laravel Storage
- **API Documentation**: Laravel Scribe
- **Icons**: Font Awesome

## 📋 Prerequisites

- PHP 8.2 or higher
- Composer
- MySQL 8.0 or higher
- Node.js & NPM (for asset compilation)

## 🚀 Installation

### 1. Clone the Repository
```bash
git clone https://github.com/yourusername/sekolah-galeri.git
cd sekolah-galeri
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure Database
Edit `.env` file and set your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sekolah_galeri
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Run Migrations and Seeders
```bash
php artisan migrate
php artisan db:seed
```

### 6. Create Storage Link
```bash
php artisan storage:link
```

### 7. Generate API Documentation
```bash
php artisan scribe:generate
```

## 🏃‍♂️ Running the Application

### Development Server
```bash
php artisan serve
```
The application will be available at `http://localhost:8000`

### Queue Worker (if using queues)
```bash
php artisan queue:work
```

### API Documentation
After running `php artisan scribe:generate`, the API documentation will be available at:
`http://localhost:8000/docs`

## 👤 Default Admin Account

After running the seeders, you can login with:
- **Username**: `admin`
- **Password**: `admin123`

## 📚 API Endpoints

### Authentication
- `POST /api/login` - Login and get token
- `POST /api/logout` - Logout and revoke token
- `GET /api/me` - Get current user info

### Guest Access (No Authentication Required)
- `GET /api/posts` - List all posts
- `GET /api/posts/{id}` - Get specific post
- `GET /api/galeries` - List all galleries
- `GET /api/galeries/{id}` - Get specific gallery
- `GET /api/fotos` - List all photos
- `GET /api/fotos/{id}` - Get specific photo
- `GET /api/profiles` - List all profiles
- `GET /api/profiles/{id}` - Get specific profile

### Admin Access (Authentication Required)
- `POST /api/posts` - Create new post
- `PUT /api/posts/{id}` - Update post
- `DELETE /api/posts/{id}` - Delete post
- `POST /api/kategori` - Create new category
- `PUT /api/kategori/{id}` - Update category
- `DELETE /api/kategori/{id}` - Delete category
- `POST /api/galeries` - Create new gallery
- `PUT /api/galeries/{id}` - Update gallery
- `DELETE /api/galeries/{id}` - Delete gallery
- `POST /api/fotos` - Upload new photo
- `PUT /api/fotos/{id}` - Update photo
- `DELETE /api/fotos/{id}` - Delete photo and file
- `POST /api/profiles` - Create new profile
- `PUT /api/profiles/{id}` - Update profile
- `DELETE /api/profiles/{id}` - Delete profile

### Admin Dashboard
- `GET /api/admin/dashboard` - Get dashboard statistics
- `GET /api/admin/categories` - Get all categories
- `GET /api/admin/posts` - Get all posts with relationships

## 🔐 Authentication

### API Authentication
The API uses Laravel Sanctum for token-based authentication:

1. **Login** to get a token:
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"username": "admin", "password": "admin123"}'
```

2. **Use the token** in subsequent requests:
```bash
curl -X GET http://localhost:8000/api/admin/dashboard \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### Web Authentication
The web interface uses session-based authentication for the admin panel.

## 📁 File Upload

Photos are uploaded to `storage/app/public/foto/` and are accessible via:
`http://localhost:8000/storage/foto/filename.jpg`

### Upload Example
```bash
curl -X POST http://localhost:8000/api/fotos \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -F "file=@photo.jpg" \
  -F "judul=My Photo" \
  -F "galery_id=1"
```

## 🗄️ Database Structure

### Tables
- `kategori` - Categories for posts
- `petugas` - Admin users
- `posts` - Articles/posts
- `galery` - Photo galleries
- `foto` - Photos with file uploads
- `profile` - School profile information
- `personal_access_tokens` - Sanctum tokens

### Relationships
- Kategori hasMany Posts
- Petugas hasMany Posts
- Post hasMany Galeries
- Galery hasMany Fotos
- Posts belongTo Kategori & Petugas
- Galery belongTo Post
- Foto belongTo Galery

## 🧪 Testing

### API Testing
You can test the API using:
- **Postman** or **Insomnia**
- **cURL** commands
- **Laravel Scribe** documentation at `/docs`

### Manual Testing
- Visit `http://localhost:8000` for guest interface
- Visit `http://localhost:8000/admin/login` for admin interface

## 📖 Documentation

- **API Documentation**: Available at `/docs` after running `php artisan scribe:generate`
- **Quick Reference**: See `API_ENDPOINTS_QUICK_REFERENCE.md`

## 🚀 Deployment

### Local Development
```bash
php artisan serve
```

### Production Deployment
1. Set `APP_ENV=production` in `.env`
2. Run `php artisan config:cache`
3. Run `php artisan route:cache`
4. Run `php artisan view:cache`
5. Ensure storage link exists: `php artisan storage:link`

### Recommended Hosting Platforms
- **Railway.app** - Easy Laravel deployment
- **Render.com** - Free tier available
- **Heroku** - Traditional choice
- **DigitalOcean App Platform** - Scalable solution

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 👨‍💻 Author

Created with ❤️ for school gallery management.

## 🆘 Support

If you encounter any issues:
1. Check the Laravel logs in `storage/logs/`
2. Ensure all dependencies are installed
3. Verify database configuration
4. Check file permissions for storage directory

---

**Happy Coding! 🎉**
