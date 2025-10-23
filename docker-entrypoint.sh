#!/bin/bash
set -e  # Stop on error

echo "🚀 Starting Laravel container..."

# Generate .env kalau belum ada
if [ ! -f .env ]; then
    echo "📄 .env not found, creating from example..."
    cp .env.example .env
fi

# Generate APP_KEY kalau belum ada atau kosong
if ! grep -q "^APP_KEY=" .env || [ -z "$(grep '^APP_KEY=' .env | cut -d= -f2-)" ]; then
    echo "🔑 Generating new APP_KEY..."
    APP_KEY=$(php artisan key:generate --show)
    sed -i "s|^APP_KEY=.*|APP_KEY=${APP_KEY}|" .env
fi

# Jalankan migrasi (aman walau gagal)
echo "🧱 Running migrations..."
php artisan migrate --force || true

# Cache konfigurasi & routes
echo "⚙️  Caching Laravel configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Pastikan permission benar
chmod -R 775 storage bootstrap/cache || true
chown -R www-data:www-data storage bootstrap/cache || true

echo "✅ Laravel ready! Serving on port ${PORT:-8080}..."
exec apache2-foreground
