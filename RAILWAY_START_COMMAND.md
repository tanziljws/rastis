# Railway Start Command Fix

## Problem
Railway is looking for `/entrypoint.sh` but we created `/start.sh`

## Solution
Create `/entrypoint.sh` in Dockerfile (Railway expects this name)

## Railway Dashboard Settings

### Option 1: Use Dockerfile CMD (Recommended)
1. Go to Railway Dashboard
2. Select your service
3. Go to Settings → Deploy
4. **Clear/Remove any custom Start Command**
5. Let Railway use Dockerfile's CMD/ENTRYPOINT

### Option 2: Set Start Command in Railway
If Railway dashboard has a start command setting, set it to:
```
/entrypoint.sh
```

Or leave it empty to use Dockerfile's CMD.

## Dockerfile Changes

1. ✅ Created `/entrypoint.sh` (Railway's expected name)
2. ✅ Set ENTRYPOINT to `/entrypoint.sh`
3. ✅ Set CMD to `/entrypoint.sh` (fallback)

## Verification

After deployment:
1. ✅ Build completes successfully
2. ✅ Container starts without errors
3. ✅ Migrations run
4. ✅ Server starts on Railway's PORT

## Troubleshooting

### Issue: Still looking for entrypoint.sh
**Solution**: 
1. Check Railway dashboard → Settings → Deploy → Start Command
2. Clear any custom start command
3. Redeploy

### Issue: Port conflicts
**Solution**: Railway sets $PORT automatically, no manual config needed

### Issue: Migrations fail
**Solution**: Check database connection variables in Railway dashboard

