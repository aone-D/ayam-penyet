FROM php:8.4-fpm

# Install dependencies sistem
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nginx \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy seluruh project
COPY . .

# Install dependencies PHP (tanpa dev dependencies)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copy konfigurasi nginx
COPY nginx.conf /etc/nginx/sites-available/default

# Set permission untuk storage & cache
RUN chmod -R 775 storage bootstrap/cache

EXPOSE 80

# Jalankan nginx dan php-fpm bersamaan
CMD service nginx start && php-fpm