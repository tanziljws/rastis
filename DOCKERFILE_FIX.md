# Dockerfile Fix for Railway

## Problem
```
ERROR: failed to build: process "/bin/sh -c composer install" did not complete successfully: exit code: 1
Could not open input file: artisan
```

## Root Cause
- Composer's `post-autoload-dump` script tries to run `php artisan package:discover`
- In the vendor stage, `artisan` doesn't exist yet (only composer.json/composer.lock are copied)
- This causes the build to fail

## Solution

### 1. Skip Scripts in Vendor Stage
- Added `--no-scripts` flag to `composer install` in vendor stage
- This prevents running scripts that require `artisan`

### 2. Run Scripts After Artisan Exists
- Run `composer dump-autoload` in the final stage after copying all files
- This ensures `artisan` exists before running Laravel scripts

### 3. Added GD Extension
- Added GD extension installation for image processing (Intervention Image)
- Required libraries: libpng-dev, libjpeg-dev, libfreetype6-dev

### 4. Fixed Port Configuration
- Changed hardcoded port 8080 to use `$PORT` environment variable
- Railway automatically sets `$PORT` environment variable
- Fallback to 8080 if `$PORT` is not set

## Changes Made

### Dockerfile
1. ✅ Added `--no-scripts` to composer install in vendor stage
2. ✅ Added GD extension with required libraries
3. ✅ Run `composer dump-autoload` after copying all files
4. ✅ Use `$PORT` environment variable instead of hardcoded 8080
5. ✅ Removed duplicate WORKDIR and EXPOSE directives

### .dockerignore
- ✅ Created to exclude unnecessary files from Docker build
- ✅ Reduces build context size and speeds up builds

## Build Process

1. **Vendor Stage**: Install composer dependencies (skip scripts)
2. **PHP Stage**: 
   - Install PHP extensions (PDO, MySQL, GD)
   - Copy application code
   - Copy vendor directory
   - Run composer scripts (now artisan exists)
   - Set permissions
   - Start server

## Railway Configuration

Railway will:
- ✅ Auto-detect Dockerfile
- ✅ Build the image
- ✅ Set `$PORT` environment variable
- ✅ Run the CMD on startup

## Environment Variables (Railway)

Set these in Railway dashboard:
```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app.railway.app
PORT=8080 (automatically set by Railway)

DB_CONNECTION=mysql
DB_HOST=${{MySQL.HOSTNAME}}
DB_PORT=${{MySQL.PORT}}
DB_DATABASE=${{MySQL.DATABASE}}
DB_USERNAME=${{MySQL.USERNAME}}
DB_PASSWORD=${{MySQL.PASSWORD}}
```

## Verification

After deployment, check:
- ✅ Build completes successfully
- ✅ Migrations run on startup
- ✅ Server starts on Railway's port
- ✅ Images can be uploaded and processed
- ✅ GD extension is available

## Troubleshooting

### Issue: Build still fails
**Solution**: Check Railway logs for specific error messages

### Issue: Port conflict
**Solution**: Railway automatically sets $PORT, no manual configuration needed

### Issue: GD extension not found
**Solution**: Verify GD is installed: `php -m | grep gd`

### Issue: Migrations fail
**Solution**: Check database connection variables in Railway

## Notes

- Railway uses Dockerfile if present (takes precedence over NIXPACKS)
- To use NIXPACKS instead, rename or delete Dockerfile
- GD extension is required for Intervention Image (image processing)
- File storage is ephemeral (use S3 for production)

