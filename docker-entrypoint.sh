#!/bin/bash
set -e

if [ ! -f .env ]; then
    cp .env.example .env
fi

if ! grep -q "^APP_KEY=" .env || [ "$(grep '^APP_KEY=' .env | cut -d= -f2-)" = "base64:" ]; then
    php artisan key:generate --no-interaction --force
fi

echo "Running migrations..."
php artisan migrate --force

php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Starting Apache on port ${PORT:-8080}..."
sed -i "s/Listen 80/Listen ${PORT:-8080}/" /etc/apache2/ports.conf
exec apache2-foreground
