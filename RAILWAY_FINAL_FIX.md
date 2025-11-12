# Railway Final Fix - Entrypoint Issue

## Problem
Railway is looking for `/entrypoint.sh` but can't find it, even though we created it in Dockerfile.

## Root Cause
Railway dashboard might have a **custom Start Command** that overrides the Dockerfile's ENTRYPOINT/CMD.

## Solution

### Step 1: Check Railway Dashboard Settings

1. Go to Railway Dashboard
2. Select your service
3. Go to **Settings** → **Deploy**
4. Look for **Start Command** or **Command** field
5. **Clear/Delete** any value in that field
6. **Save** the changes

### Step 2: Verify Dockerfile

The Dockerfile now:
- ✅ Creates `/entrypoint.sh` script
- ✅ Makes it executable
- ✅ Sets ENTRYPOINT to `/bin/sh /entrypoint.sh`

### Step 3: Redeploy

After clearing the start command:
1. Push your code again
2. Railway will use Dockerfile's ENTRYPOINT
3. Container should start successfully

## Alternative: Set Start Command in Railway

If you need to set it manually in Railway dashboard, use:
```
/bin/sh /entrypoint.sh
```

Or simply:
```
/entrypoint.sh
```

## Dockerfile Entrypoint Script

The `/entrypoint.sh` script does:
1. Generate app key (if not exists)
2. Create storage link
3. Run migrations
4. Cache config, routes, views
5. Start Laravel server on Railway's PORT

## Verification

After fixing:
- ✅ Build completes successfully
- ✅ Container starts without "entrypoint.sh not found" error
- ✅ Migrations run on startup
- ✅ Server starts on Railway's PORT
- ✅ Application is accessible

## Troubleshooting

### Issue: Still getting "entrypoint.sh not found"
**Solutions**:
1. Check Railway dashboard → Settings → Deploy → Start Command
2. Clear any custom start command
3. Make sure Dockerfile has ENTRYPOINT set
4. Redeploy the service

### Issue: Port conflicts
**Solution**: Railway automatically sets $PORT, no manual config needed

### Issue: Migrations fail
**Solution**: 
1. Check database connection variables
2. Verify MySQL service is connected
3. Check Railway logs for specific errors

### Issue: Permission denied
**Solution**: Script is already chmod +x in Dockerfile, but verify in Railway logs

## Railway Dashboard Settings

### Recommended Settings:
- **Start Command**: (empty - use Dockerfile ENTRYPOINT)
- **Health Check Path**: `/`
- **Health Check Timeout**: 100

### Environment Variables:
```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app.railway.app
PORT=8080 (auto-set by Railway)

DB_CONNECTION=mysql
DB_HOST=${{MySQL.HOSTNAME}}
DB_PORT=${{MySQL.PORT}}
DB_DATABASE=${{MySQL.DATABASE}}
DB_USERNAME=${{MySQL.USERNAME}}
DB_PASSWORD=${{MySQL.PASSWORD}}
```

## Next Steps

1. ✅ Clear Start Command in Railway dashboard
2. ✅ Push code to trigger redeploy
3. ✅ Verify container starts successfully
4. ✅ Check Railway logs for any errors
5. ✅ Test application functionality

## Notes

- Railway uses Dockerfile ENTRYPOINT/CMD if no custom start command is set
- Custom start command in dashboard overrides Dockerfile
- Always check Railway dashboard settings if deployment fails
- File storage is ephemeral (use S3 for production)

