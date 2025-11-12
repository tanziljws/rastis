#!/bin/sh
set -e

echo "🚀 Starting Laravel application..."

# Generate app key if not exists
php artisan key:generate --force || true

# Create storage link (remove existing if it's a directory)
if [ -d public/storage ]; then
    rm -rf public/storage
fi
php artisan storage:link || true

# Ensure storage directories exist and have correct permissions
mkdir -p storage/app/public/fotos
mkdir -p storage/app/public/fotos/thumbnails
mkdir -p storage/app/public/hero-backgrounds
mkdir -p storage/app/public/logos
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Fix permissions for storage/app/public and all subdirectories
chmod -R 755 storage/app/public
find storage/app/public -type f -exec chmod 644 {} \;
find storage/app/public -type d -exec chmod 755 {} \;

# Ensure fotos directory is writable
chmod -R 775 storage/app/public/fotos
chmod -R 775 storage/app/public/fotos/thumbnails

# Fix permissions for all existing files in fotos directory
if [ -d storage/app/public/fotos ]; then
    find storage/app/public/fotos -type f -exec chmod 644 {} \; 2>/dev/null || true
    find storage/app/public/fotos -type d -exec chmod 755 {} \; 2>/dev/null || true
fi

# Ensure public/storage has correct permissions
if [ -L public/storage ]; then
    chmod -R 755 public/storage
    find public/storage -type f -exec chmod 644 {} \; 2>/dev/null || true
    find public/storage -type d -exec chmod 755 {} \; 2>/dev/null || true
    
    # Fix permissions for fotos in public/storage
    if [ -d public/storage/fotos ]; then
        find public/storage/fotos -type f -exec chmod 644 {} \; 2>/dev/null || true
        find public/storage/fotos -type d -exec chmod 755 {} \; 2>/dev/null || true
    fi
fi

# Create .htaccess in public/storage if it doesn't exist (for Apache)
if [ ! -f public/storage/.htaccess ]; then
    cat > public/storage/.htaccess << 'EOF'
# Allow access to all files in storage
<IfModule mod_rewrite.c>
    RewriteEngine Off
</IfModule>

# Allow access to all file types
<FilesMatch ".*">
    Order allow,deny
    Allow from all
</FilesMatch>
EOF
    chmod 644 public/storage/.htaccess
fi

# Run migrations
php artisan migrate --force || true

# Fix photo permissions (run after migrations to ensure all files are accessible)
php artisan fotos:fix-permissions || true

# Cache configuration
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Get port from environment or use default
PORT="${PORT:-8080}"

echo "✅ Starting server on port $PORT"

# Start Laravel server
exec php artisan serve --host=0.0.0.0 --port="$PORT"

