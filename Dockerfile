# Gunakan image resmi PHP + Apache
FROM php:8.2-apache

# Install dependencies
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

# Enable Apache rewrite
RUN a2enmod rewrite

# Install Node.js 20 & Composer
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy semua file project ke container
COPY . /var/www/html

# Pastikan folder penting Laravel ada
RUN mkdir -p storage/framework/views storage/framework/cache storage/app storage/logs bootstrap/cache

# Install dependencies Laravel
RUN composer install --optimize-autoloader --no-dev

# Build frontend (Vite, Mix, dll)
RUN npm install && npm run build

# Set permission agar Apache bisa akses file Laravel
RUN chmod -R 775 storage bootstrap/cache public/build \
    && chown -R www-data:www-data storage bootstrap/cache public/build

# Set DocumentRoot Apache ke folder Laravel "public"
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Ubah konfigurasi Apache dan tambahkan ServerName
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf && \
    echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Railway akan inject PORT env, default ke 8080
EXPOSE ${PORT:-8080}

# Copy dan beri izin untuk entrypoint
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Jalankan entrypoint
ENTRYPOINT ["docker-entrypoint.sh"]
