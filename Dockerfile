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

# Copy composer binary from composer image
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application code
COPY . /var/www/html

# Copy vendor from builder stage
COPY --from=vendor /app/vendor /var/www/html/vendor

# Copy entrypoint script
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Run Laravel package discovery now that artisan exists
RUN php artisan package:discover --ansi || echo "Package discovery skipped"

# Ensure storage/framework is writable
RUN mkdir -p storage/framework/{cache,sessions,views} \
    && chmod -R 775 storage bootstrap/cache

# Set Laravel environment to production
ENV APP_ENV=production \
    PHP_CLI_SERVER_WORKERS=4

# Expose port (Railway will set $PORT)
EXPOSE 8080

# Use entrypoint with shell (ensures env vars are available)
ENTRYPOINT ["/bin/sh", "/entrypoint.sh"]
