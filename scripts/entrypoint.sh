#!/usr/bin/env sh
set -euo pipefail

echo "[entrypoint] Bootstrapping Laravel..."

# Create .env if missing
if [ ! -f .env ]; then
  echo "[entrypoint] No .env found. Copying from .env.example"
  cp .env.example .env || true
fi

# App key
php artisan key:generate --force || true

# Storage link
php artisan storage:link || true

# Optimize caches
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Run migrations (safe on already migrated DB)
php artisan migrate --force || true

echo "[entrypoint] Starting Laravel server on 0.0.0.0:8080"
php -S 0.0.0.0:8080 -t public public/index.php




