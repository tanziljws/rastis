# Railway Deployment Guide

## Prerequisites

1. Railway account
2. MySQL database (Railway provides this)
3. (Optional) S3/Cloud Storage for file storage

## Deployment Steps

### 1. Setup Railway Project

1. Create new project in Railway
2. Add MySQL database service
3. Add PHP service
4. Connect to your GitHub repository

### 2. Environment Variables

Set these in Railway dashboard:

```env
APP_NAME="Galeri Sekolah"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app.railway.app
APP_KEY=base64:YOUR_APP_KEY_HERE

DB_CONNECTION=mysql
DB_HOST=${{MySQL.HOSTNAME}}
DB_PORT=${{MySQL.PORT}}
DB_DATABASE=${{MySQL.DATABASE}}
DB_USERNAME=${{MySQL.USERNAME}}
DB_PASSWORD=${{MySQL.PASSWORD}}

# For S3 Storage (Recommended)
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your_key
AWS_SECRET_ACCESS_KEY=your_secret
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket

# Or use Railway Volume (Alternative)
# FILESYSTEM_DISK=local
```

### 3. Build Configuration

Create `railway.toml` in project root:

```toml
[build]
builder = "NIXPACKS"

[deploy]
startCommand = "php artisan serve --host=0.0.0.0 --port=$PORT"
healthcheckPath = "/"
healthcheckTimeout = 100
restartPolicyType = "ON_FAILURE"
restartPolicyMaxRetries = 10
```

### 4. Build Script (Optional)

Create `build.sh`:

```bash
#!/bin/bash

# Install dependencies
composer install --optimize-autoloader --no-dev --no-interaction

# Generate app key if not set
php artisan key:generate --force

# Run migrations
php artisan migrate --force

# Generate thumbnails for existing photos
php artisan fotos:generate-thumbnails

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link
php artisan storage:link
```

### 5. Storage Setup

#### Option A: S3/Cloud Storage (Recommended)

1. Create S3 bucket
2. Set environment variables
3. Update `config/filesystems.php` if needed
4. Files will be stored in cloud

#### Option B: Railway Volume

1. Add volume to Railway service
2. Mount to `/app/storage`
3. Files will persist across deployments

#### Option C: Ephemeral Storage (Not Recommended)

- Files will be lost on redeploy
- Only for testing

### 6. Database Migration

Railway will automatically run migrations on deploy if you add to build script:

```bash
php artisan migrate --force
```

### 7. Generate Thumbnails

After first deployment, generate thumbnails for existing photos:

```bash
php artisan fotos:generate-thumbnails
```

### 8. Verify Deployment

1. Check application URL
2. Verify database connection
3. Test file uploads
4. Check image compression
5. Verify thumbnails are generated

## Post-Deployment

### 1. Clear Caches

```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 2. Generate Thumbnails

```bash
php artisan fotos:generate-thumbnails
```

### 3. Monitor Performance

- Check Railway metrics
- Monitor database queries
- Check file storage usage
- Monitor error logs

## Troubleshooting

### Issue: Files Not Persisting

**Solution**: Use S3 or Railway Volume for storage

### Issue: Image Processing Fails

**Solution**: Ensure GD or Imagick extension is installed

### Issue: Thumbnails Not Generated

**Solution**: Run `php artisan fotos:generate-thumbnails`

### Issue: Database Connection Failed

**Solution**: Check environment variables and database service status

### Issue: 500 Error

**Solution**: 
1. Check logs in Railway dashboard
2. Verify APP_DEBUG=false in production
3. Check file permissions
4. Verify storage link exists

## Performance Optimization

### 1. Enable Query Caching

Add to `config/database.php`:

```php
'cache' => [
    'store' => 'redis',
    'prefix' => 'db_',
],
```

### 2. Use Redis for Cache

```env
CACHE_DRIVER=redis
REDIS_HOST=${{Redis.HOSTNAME}}
REDIS_PASSWORD=${{Redis.PASSWORD}}
REDIS_PORT=${{Redis.PORT}}
```

### 3. Enable OPcache

Railway PHP build includes OPcache by default

### 4. CDN for Static Assets

Use CDN for:
- CSS files
- JavaScript files
- Images (if using S3)

## Security Checklist

- [ ] APP_DEBUG=false
- [ ] APP_ENV=production
- [ ] Strong database passwords
- [ ] S3 bucket permissions set correctly
- [ ] CORS configured if needed
- [ ] Rate limiting enabled
- [ ] HTTPS enabled (Railway provides this)

## Monitoring

### Railway Metrics

- CPU usage
- Memory usage
- Network traffic
- Request count
- Error rate

### Application Logs

```bash
# View logs in Railway dashboard
# Or use Railway CLI
railway logs
```

## Backup Strategy

1. **Database**: Railway provides automatic backups
2. **Files**: S3 versioning enabled
3. **Code**: GitHub repository

## Scaling

### Horizontal Scaling

1. Add more Railway services
2. Use load balancer
3. Share session storage (Redis)

### Vertical Scaling

1. Increase Railway service resources
2. Optimize database queries
3. Use caching aggressively

## Cost Optimization

1. Use S3 for file storage (cheaper than Railway Volume)
2. Enable query caching
3. Optimize image sizes
4. Use CDN for static assets
5. Monitor resource usage

## Support

- Railway Docs: https://docs.railway.app
- Laravel Docs: https://laravel.com/docs
- Intervention Image: https://image.intervention.io

