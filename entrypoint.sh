#!/bin/sh

PORT=${PORT:-8080}
echo "Configuring Nginx to listen on port ${PORT}..."
sed -i "s/8080/${PORT}/g" /etc/nginx/http.d/default.conf

# Generate APP_KEY if missing
if [ -z "$APP_KEY" ]; then
    echo "Generating application key..."
    php artisan key:generate --force || true
fi

# Ensure SQLite database file exists with permissions
touch /var/www/html/database/database.sqlite
chmod -R 777 /var/www/html/database
chmod -R 777 /var/www/html/storage
chmod -R 777 /var/www/html/bootstrap/cache

# Run migrations and seed
echo "Running migrations..."
php artisan migrate --force || true
php artisan db:seed --force || true

# Clear previous caches and recreate
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Start PHP-FPM
echo "Starting PHP-FPM..."
php-fpm -D

# Start Nginx in foreground
echo "Starting Nginx on port ${PORT}..."
exec nginx -g "daemon off;"