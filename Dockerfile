# Simple production-ish Dockerfile for Laravel (PHP built-in server)
# Suitable for quick deploys on Railway/Render. For heavy traffic, use Nginx+PHP-FPM.

FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock /app/
RUN composer install --no-dev --prefer-dist --no-progress --no-interaction --optimize-autoloader

FROM php:8.2-cli

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www/html

# Copy application code
COPY . /var/www/html

# Copy vendor from builder stage
COPY --from=vendor /app/vendor /var/www/html/vendor

# Ensure storage/framework is writable
RUN mkdir -p storage/framework/{cache,sessions,views} \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Set Laravel environment to production by default (override via env)
ENV APP_ENV=production \
    PHP_CLI_SERVER_WORKERS=4

# Expose port for PHP built-in server
EXPOSE 8080

# Entry script handles cache, storage link, and migrations before serving
COPY scripts/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

CMD ["/entrypoint.sh"]




