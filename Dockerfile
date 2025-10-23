# Gunakan base image PHP + Apache
FROM php:8.2-apache

# Install dependensi sistem & ekstensi PHP
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd

# Aktifkan mod_rewrite untuk Laravel
RUN a2enmod rewrite

# Install Node.js 20 dan Composer
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy semua file aplikasi
COPY . /var/www/html

# Pastikan folder penting ada
RUN mkdir -p storage/framework/views storage/framework/cache storage/app storage/logs bootstrap/cache

# Install dependensi PHP
RUN composer install --optimize-autoloader --no-dev

# Build frontend (kalau ada, misalnya dari Laravel Mix / Vite)
RUN npm install && npm run build || echo "⚠️  Skip npm build (no frontend)"

# Permission untuk Laravel
RUN chmod -R 775 storage bootstrap/cache public/build || true \
    && chown -R www-data:www-data storage bootstrap/cache public/build || true

# Set public folder jadi root Apache
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Port Railway (gunakan 8080)
EXPOSE 8080

# Copy entrypoint custom
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Jalankan entrypoint
ENTRYPOINT ["docker-entrypoint.sh"]
