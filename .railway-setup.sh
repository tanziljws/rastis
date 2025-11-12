#!/bin/bash
# Railway setup script - runs before the app starts

echo "🚀 Railway Setup Script"

# Generate app key if not set
php artisan key:generate --force || true

# Create storage link
php artisan storage:link || true

# Run migrations
php artisan migrate --force || true

# Generate thumbnails for existing photos (if needed)
php artisan fotos:generate-thumbnails || true

# Cache configuration
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "✅ Setup complete!"

