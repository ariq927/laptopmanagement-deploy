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

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install Node.js 20
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

# Copy project files
COPY . /var/www/html

# Create necessary Laravel directories
RUN mkdir -p storage/framework/views storage/framework/cache storage/app storage/logs bootstrap/cache

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Build frontend assets
RUN npm install && npm run build

# Set permissions
RUN chmod -R 775 storage bootstrap/cache public/build \
    && chown -R www-data:www-data storage bootstrap/cache public/build

# Set Apache document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Update Apache configs to use the new doc root
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Make Apache listen to the dynamic Railway port
RUN echo "Listen ${PORT}" >> /etc/apache2/ports.conf

# Expose port (default to 8080 if Railway doesn’t set $PORT)
EXPOSE ${PORT:-8080}

# Copy entrypoint
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
