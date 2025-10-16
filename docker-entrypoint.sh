#!/bin/bash
set -e  # Exit on error

# Generate .env kalau belum ada (dari .env.example), dan set APP_KEY
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Generate APP_KEY kalau kosong (Railway inject via ENV, tapi fallback)
if ! grep -q "^APP_KEY=" .env || [ "$(grep '^APP_KEY=' .env | cut -d= -f2-)" = "base64:" ]; then
    php artisan key:generate --no-interaction --force --show | sed 's/^APP_KEY=/APP_KEY=/' >> .env
fi

# Jalankan migrasi (force untuk production)
echo "Running migrations..."
php artisan migrate --force

# Opsional: Cache config/routes untuk performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start Apache (bind otomatis ke port 80, Railway handle $PORT via proxy)
echo "Starting Apache on port ${PORT:-8080}..."
exec apache2-foreground