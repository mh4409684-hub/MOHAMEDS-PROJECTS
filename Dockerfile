FROM php:8.2-fpm-alpine

# Install system dependencies & extensions
RUN apk add --no-cache \
    nginx \
    curl \
    git \
    unzip \
    dos2unix \
    libzip-dev \
    sqlite-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_sqlite zip gd opcache

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Convert Windows CRLF to Unix LF for shell scripts
RUN dos2unix entrypoint.sh render-nginx.conf \
    && chmod +x entrypoint.sh

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Create database file if not exists
RUN touch database/database.sqlite \
    && chmod -R 775 storage bootstrap/cache database \
    && chown -R www-data:www-data storage bootstrap/cache database

# Copy Nginx configuration
RUN mkdir -p /run/nginx
COPY render-nginx.conf /etc/nginx/http.d/default.conf

# Expose port (Render uses $PORT or default 8080)
EXPOSE 8080

CMD ["/bin/sh", "/var/www/html/entrypoint.sh"]
