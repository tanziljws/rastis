#!/bin/bash

echo "🔍 Verifying Railway Setup..."
echo ""

# Check storage link
echo "1. Checking storage link..."
if [ -L public/storage ]; then
    echo "   ✅ Storage link exists"
    ls -la public/storage | head -1
else
    echo "   ❌ Storage link missing!"
    echo "   Run: php artisan storage:link"
fi

echo ""

# Check storage directories
echo "2. Checking storage directories..."
if [ -d storage/app/public/fotos ]; then
    echo "   ✅ fotos directory exists"
    echo "   Files: $(ls storage/app/public/fotos 2>/dev/null | wc -l | xargs) files"
else
    echo "   ❌ fotos directory missing!"
fi

if [ -d storage/app/public/fotos/thumbnails ]; then
    echo "   ✅ thumbnails directory exists"
else
    echo "   ⚠️  thumbnails directory missing (will be created)"
fi

echo ""

# Check permissions
echo "3. Checking file permissions..."
if [ -d storage/app/public/fotos ]; then
    PERM=$(stat -f "%OLp" storage/app/public/fotos 2>/dev/null || stat -c "%a" storage/app/public/fotos 2>/dev/null)
    if [ "$PERM" = "755" ] || [ "$PERM" = "775" ]; then
        echo "   ✅ Directory permissions OK: $PERM"
    else
        echo "   ⚠️  Directory permissions: $PERM (should be 755 or 775)"
    fi
fi

echo ""

# Check CORS config
echo "4. Checking CORS configuration..."
if [ -f config/cors.php ]; then
    echo "   ✅ CORS config exists"
    if grep -q "'paths' => \['api/\*', 'admin/\*'" config/cors.php; then
        echo "   ✅ CORS paths configured"
    else
        echo "   ⚠️  CORS paths may need update"
    fi
else
    echo "   ❌ CORS config missing!"
fi

echo ""

# Check filesystem config
echo "5. Checking filesystem configuration..."
if grep -q "'default' => env('FILESYSTEM_DISK', 'public')" config/filesystems.php; then
    echo "   ✅ Default filesystem disk: public"
else
    echo "   ⚠️  Default filesystem may not be 'public'"
fi

echo ""

# Check .env
echo "6. Checking .env configuration..."
if [ -f .env ]; then
    if grep -q "APP_URL=" .env; then
        APP_URL=$(grep "APP_URL=" .env | cut -d '=' -f2)
        echo "   ✅ APP_URL: $APP_URL"
    else
        echo "   ⚠️  APP_URL not set"
    fi
    
    if grep -q "FILESYSTEM_DISK=" .env; then
        FILESYSTEM=$(grep "FILESYSTEM_DISK=" .env | cut -d '=' -f2)
        echo "   ✅ FILESYSTEM_DISK: $FILESYSTEM"
    else
        echo "   ⚠️  FILESYSTEM_DISK not set (will use default: public)"
    fi
else
    echo "   ⚠️  .env file not found"
fi

echo ""
echo "✅ Verification complete!"
echo ""
echo "📋 Next Steps:"
echo "1. Setup Railway Volume: /app/storage/app/public"
echo "2. Deploy to Railway"
echo "3. Test upload foto"
echo "4. Test hapus foto"
echo "5. Verify foto tidak hilang setelah restart"

