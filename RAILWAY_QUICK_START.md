# Railway Quick Start Guide

## ✅ Fixed Configuration

### Problem Solved
- ❌ Old: `railway.json` with invalid builder format
- ✅ New: Railway auto-detects with NIXPACKS

## 📁 Configuration Files

### 1. `nixpacks.toml`
- Defines PHP 8.2 with all required extensions
- Includes GD extension for image processing
- Configures build and start commands

### 2. `Procfile`
- Start command for Railway
- Uses PHP built-in server
- Automatically uses Railway's $PORT

## 🚀 Deployment Steps

### 1. Push to Railway
```bash
git add .
git commit -m "Fix Railway deployment"
git push
```

### 2. Railway Auto-Detection
Railway will:
- ✅ Detect PHP/Laravel project
- ✅ Use NIXPACKS builder
- ✅ Install PHP 8.2 with extensions
- ✅ Run `composer install`
- ✅ Execute build commands
- ✅ Start Laravel server

### 3. Set Environment Variables (Railway Dashboard)
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

### 4. Add MySQL Database (Railway Dashboard)
1. Add MySQL service to your project
2. Railway will automatically provide connection variables
3. Use `${{MySQL.HOSTNAME}}` format in environment variables

### 5. After Deployment
- Migrations run automatically on startup
- Generate thumbnails: Connect via Railway CLI or SSH
- Set up file storage (S3 recommended)

## 🔍 Verify Deployment

1. Check Railway logs for:
   - ✅ Composer install successful
   - ✅ Migrations completed
   - ✅ Server started on port $PORT

2. Visit your app URL
3. Test features:
   - ✅ Homepage loads
   - ✅ Gallery displays
   - ✅ Image upload works
   - ✅ Thumbnails generate

## 🛠️ Troubleshooting

### Issue: Build fails
**Check**: Railway logs for PHP extension errors
**Solution**: Verify `nixpacks.toml` has all required extensions

### Issue: Migrations fail
**Check**: Database connection variables
**Solution**: Verify MySQL service is connected and variables are set

### Issue: Images not uploading
**Check**: Storage permissions and file system
**Solution**: Use S3 or Railway Volume for persistent storage

### Issue: 500 Error
**Check**: Railway logs for Laravel errors
**Solution**: Set `APP_DEBUG=true` temporarily to see errors

## 📝 Notes

- Railway uses NIXPACKS by default (no configuration needed)
- File storage is ephemeral (use S3 for production)
- Migrations run automatically on each deploy
- Cache is cleared on each deploy (rebuilds automatically)

## 🎯 Next Steps

1. ✅ Deploy to Railway
2. ✅ Set environment variables
3. ✅ Connect MySQL database
4. ✅ Test application
5. ✅ Set up S3 for file storage
6. ✅ Generate thumbnails for existing photos
7. ✅ Monitor performance

