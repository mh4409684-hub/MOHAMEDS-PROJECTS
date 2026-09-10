#!/bin/sh
set -e

# Run migrations if necessary
php artisan migrate --force --seed || true

# Cache configurations for speed
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start PHP-FPM
php-fpm -D

# Start Nginx in foreground
exec nginx -g "daemon off;"