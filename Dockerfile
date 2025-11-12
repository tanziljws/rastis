# Production Dockerfile for Laravel on Railway
# Uses multi-stage build for optimized image size

# Stage 1: Install Composer dependencies
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock /app/
# Skip scripts that require artisan (will run in final stage)
RUN composer install --no-dev --prefer-dist --no-progress --no-interaction --optimize-autoloader --no-scripts

# Stage 2: Build PHP image with extensions
FROM php:8.2-cli

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo pdo_mysql gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /var/www/html

# Copy application code
COPY . /var/www/html

# Copy vendor from builder stage
COPY --from=vendor /app/vendor /var/www/html/vendor

# Run composer scripts now that artisan exists
RUN composer dump-autoload --optimize --classmap-authoritative || true

# Ensure storage/framework is writable
RUN mkdir -p storage/framework/{cache,sessions,views} \
    && chmod -R 775 storage bootstrap/cache

# Set Laravel environment to production
ENV APP_ENV=production \
    PHP_CLI_SERVER_WORKERS=4

# Expose port (Railway will set $PORT)
EXPOSE 8080

# Start script - runs migrations and starts server
CMD sh -c "php artisan key:generate --force || true && \
           php artisan storage:link || true && \
           php artisan migrate --force || true && \
           php artisan config:cache && \
           php artisan route:cache && \
           php artisan view:cache && \
           php artisan serve --host=0.0.0.0 --port=\${PORT:-8080}"
