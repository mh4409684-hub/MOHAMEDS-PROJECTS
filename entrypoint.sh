#!/bin/sh
set -e

PORT=${PORT:-8080}
echo "Configuring Nginx to listen on port ${PORT}..."
sed -i "s/8080/${PORT}/g" /etc/nginx/http.d/default.conf

# Generate APP_KEY if missing
if [ -z "$APP_KEY" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Run migrations and seed data
echo "Running migrations..."
php artisan migrate --force || true

# Cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start PHP-FPM
echo "Starting PHP-FPM..."
php-fpm -D

# Start Nginx in foreground
echo "Starting Nginx on port ${PORT}..."
exec nginx -g "daemon off;"