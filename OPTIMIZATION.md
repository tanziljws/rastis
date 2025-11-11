# Production Optimization Guide

## ✅ Completed Optimizations

### 1. Database Indexes
- Added indexes to frequently queried columns:
  - `foto`: judul, kategori_id, galery_id, created_at, batch_id
  - `likes`: foto_id, user_id
  - `comments`: foto_id, user_id, created_at
  - `galery`: status, post_id
  - `posts`: status, kategori_id, created_at

### 2. Image Optimization
- ✅ Image compression (max 1920px width, 85% quality)
- ✅ Automatic thumbnail generation (400x400px)
- ✅ PNG to JPEG conversion (when no transparency)
- ✅ Gallery uses thumbnails, detail pages use full size
- ✅ Lazy loading implemented for all images

### 3. Caching
- ✅ Config cache
- ✅ Route cache
- ✅ View cache
- ✅ Query caching for categories (1 hour)

### 4. Code Optimization
- ✅ Eager loading (with()) to prevent N+1 queries
- ✅ Pagination for all lists
- ✅ Removed debug code (console.log, dd, dump)
- ✅ Optimized file URL accessors (no file_exists checks)

### 5. Dependencies
- ✅ Composer optimize autoloader
- ✅ Production dependencies only

## 🚀 Deployment Steps

### 1. Run Migrations
```bash
php artisan migrate --force
```

### 2. Generate Thumbnails for Existing Photos
```bash
php artisan fotos:generate-thumbnails
```

### 3. Optimize for Production
```bash
bash optimize-production.sh
```

Or manually:
```bash
# Clear caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Optimize autoloader
composer install --optimize-autoloader --no-dev

# Cache everything
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 4. Environment Variables
Set in Railway:
```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app.railway.app
```

## 📦 File Storage for Railway

**Important**: Railway filesystem is not persistent. Consider using:

1. **S3/Cloud Storage** (Recommended)
   - AWS S3
   - DigitalOcean Spaces
   - Cloudinary

2. **Railway Volumes** (For persistent storage)
   - Mount volume to `/app/storage`

3. **Update .env**:
```env
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your_key
AWS_SECRET_ACCESS_KEY=your_secret
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket
```

## 🔍 Performance Monitoring

### Check Query Performance
```bash
# Enable query logging
DB_LOG_QUERIES=true
```

### Monitor Cache Hit Rate
- Check cache stats in admin panel
- Monitor Redis/Memcached if using

## 🛠️ Maintenance Commands

### Regenerate Thumbnails
```bash
php artisan fotos:generate-thumbnails
```

### Force Regenerate All Thumbnails
```bash
php artisan fotos:generate-thumbnails --force
```

### Clear All Caches
```bash
php artisan optimize:clear
```

## 📊 Expected Performance Improvements

- **Image Size**: 60-80% reduction
- **Page Load Time**: 40-60% faster
- **Database Queries**: 50-70% reduction (with indexes)
- **Memory Usage**: 30-40% reduction

## ⚠️ Notes

1. **Image Processing**: Requires GD or Imagick extension
2. **Storage**: Railway filesystem is ephemeral - use S3 for production
3. **Cache**: Clear cache after code updates
4. **Thumbnails**: Generate thumbnails for existing photos after migration

## 🔄 Rollback

If issues occur:

```bash
# Rollback migrations
php artisan migrate:rollback --step=2

# Clear caches
php artisan optimize:clear

# Remove thumbnails
rm -rf storage/app/public/fotos/thumbnails
```

