#!/bin/bash

# Production Optimization Script for Railway Deployment

echo "🚀 Starting production optimization..."

# 1. Clear all caches
echo "📦 Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# 2. Optimize autoloader
echo "⚡ Optimizing composer autoloader..."
composer install --optimize-autoloader --no-dev --no-interaction

# 3. Cache configuration
echo "💾 Caching configuration..."
php artisan config:cache

# 4. Cache routes
echo "🛣️  Caching routes..."
php artisan route:cache

# 5. Cache views
echo "👁️  Caching views..."
php artisan view:cache

# 6. Run migrations
echo "🗄️  Running migrations..."
php artisan migrate --force

# 7. Generate thumbnail for existing photos (if needed)
echo "🖼️  Generating thumbnails for existing photos..."
php artisan tinker --execute="
use App\Models\Foto;
use App\Services\ImageService;
\$fotos = Foto::whereNull('thumbnail')->get();
foreach (\$fotos as \$foto) {
    try {
        \$fullPath = storage_path('app/public/' . \$foto->file);
        if (file_exists(\$fullPath)) {
            \$thumbnailPath = ImageService::generateThumbnail(\$fullPath, 'fotos', 400);
            \$foto->update(['thumbnail' => \$thumbnailPath]);
            echo 'Generated thumbnail for: ' . \$foto->id . PHP_EOL;
        }
    } catch (\Exception \$e) {
        echo 'Error generating thumbnail for ' . \$foto->id . ': ' . \$e->getMessage() . PHP_EOL;
    }
}
"

echo "✅ Production optimization complete!"

