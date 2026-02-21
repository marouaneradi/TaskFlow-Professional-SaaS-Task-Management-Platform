#!/bin/sh
set -e

echo "==> Starting TaskFlow..."

# Wait for database to be ready
echo "==> Waiting for database..."
until php -r "new PDO('mysql:host=${DB_HOST:-127.0.0.1};port=${DB_PORT:-3306};dbname=${DB_DATABASE:-taskflow}', '${DB_USERNAME:-root}', '${DB_PASSWORD:-}');" 2>/dev/null; do
    echo "Waiting for MySQL..."
    sleep 2
done
echo "==> Database is ready!"

# Generate app key if not set
if [ -z "$APP_KEY" ]; then
    echo "==> Generating application key..."
    php artisan key:generate --force
fi

# Run migrations
echo "==> Running database migrations..."
php artisan migrate --force

# Seed if first run (optional)
if [ "$SEED_DATABASE" = "true" ]; then
    echo "==> Seeding database..."
    php artisan db:seed --force
fi

# Clear and cache configuration
echo "==> Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Start PHP-FPM
echo "==> Starting PHP-FPM..."
php-fpm -D

# Start Nginx
echo "==> Starting Nginx..."
nginx -g "daemon off;"
