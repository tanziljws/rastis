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
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Run migrations
php artisan migrate --force || true

# Cache configuration
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Get port from environment or use default
PORT="${PORT:-8080}"

echo "✅ Starting server on port $PORT"

# Start Laravel server
exec php artisan serve --host=0.0.0.0 --port="$PORT"

