# Railway Deployment Fix

## Problem
```
Failed to parse your service config. Error: build.builder: Invalid input
```

## Solution

### 1. Removed Invalid `railway.json`
- The old `railway.json` used invalid builder format
- Deleted the file

### 2. Railway Auto-Detection
- Railway will auto-detect PHP/Laravel project
- Uses NIXPACKS builder automatically
- Reads `nixpacks.toml` for custom configuration

### 3. Updated `nixpacks.toml`
- Added PHP 8.2 with all required extensions
- Includes GD extension for image processing (Intervention Image)
- Includes PDO MySQL for database
- Configured build phases

### 4. Updated `Procfile`
- Simplified to use PHP built-in server
- Uses Railway's $PORT environment variable

## Files Changed

1. ✅ Deleted `railway.json` (invalid format)
2. ✅ Updated `nixpacks.toml` (PHP extensions + build commands)
3. ✅ Updated `Procfile` (start command)
4. ✅ Railway will auto-detect and use NIXPACKS

## Deployment Steps

### 1. Push to Railway
```bash
git add .
git commit -m "Fix Railway deployment configuration"
git push
```

### 2. Railway Will Auto-Detect
- Railway will use NIXPACKS builder
- Will install PHP 8.2 with extensions
- Will run composer install
- Will start Laravel server

### 3. Environment Variables (Set in Railway Dashboard)
```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app.railway.app
DB_CONNECTION=mysql
DB_HOST=${{MySQL.HOSTNAME}}
DB_PORT=${{MySQL.PORT}}
DB_DATABASE=${{MySQL.DATABASE}}
DB_USERNAME=${{MySQL.USERNAME}}
DB_PASSWORD=${{MySQL.PASSWORD}}
```

### 4. After First Deploy
- Migrations will run automatically
- Generate thumbnails: `php artisan fotos:generate-thumbnails`
- Set up file storage (S3 or Railway Volume)

## Verification

After deployment, check:
- ✅ Application starts without errors
- ✅ Database migrations run successfully
- ✅ Storage link is created
- ✅ Images can be uploaded and processed
- ✅ Thumbnails are generated

## Troubleshooting

### Issue: PHP extensions not found
**Solution**: Check `nixpacks.toml` - all extensions are listed

### Issue: Migrations fail
**Solution**: Check database credentials in Railway environment variables

### Issue: Storage not writable
**Solution**: Railway handles permissions automatically with NIXPACKS

### Issue: Image processing fails
**Solution**: Ensure GD extension is installed (already in nixpacks.toml)

## Notes

- Railway uses NIXPACKS by default for PHP projects
- No Dockerfile needed (but can be used if preferred)
- File storage: Use S3 or Railway Volume (not local storage)
- Environment variables: Set in Railway dashboard

