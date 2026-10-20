#!/bin/sh

PORT=${PORT:-8080}
echo "Configuring Nginx to listen on port ${PORT}..."
sed -i "s/8080/${PORT}/g" /etc/nginx/http.d/default.conf

# Generate APP_KEY if missing
if [ -z "$APP_KEY" ]; then
    echo "Generating application key..."
    php artisan key:generate --force || true
fi

# Create required directories before anything else
mkdir -p /var/www/html/storage/logs \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/framework/cache \
         /var/www/html/bootstrap/cache \
         /var/www/html/database

touch /var/www/html/storage/logs/laravel.log

# Ensure database permissions
if [ "$DB_CONNECTION" = "sqlite" ] || [ -z "$DB_CONNECTION" ] && [ -z "$DATABASE_URL" ]; then
    touch /var/www/html/database/database.sqlite
fi

# Run migrations and seed
echo "Running migrations..."
php artisan migrate --force || true
php artisan db:seed --force || true

# Clear previous caches and recreate
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Ensure all permissions are owned by www-data and fully writable after all artisan root operations
echo "Enforcing www-data ownership and 777 permissions across storage, cache, and database..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
chmod 666 /var/www/html/storage/logs/laravel.log 2>/dev/null || true

# Start PHP-FPM
echo "Starting PHP-FPM..."
php-fpm -D

# Start Nginx in foreground
echo "Starting Nginx on port ${PORT}..."
exec nginx -g "daemon off;"