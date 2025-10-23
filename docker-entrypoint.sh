#!/bin/bash
set -e  # Exit kalau ada error

echo "🚀 Starting Laravel container..."

# Generate .env kalau belum ada
if [ ! -f .env ]; then
    echo "📄 .env not found, creating from example..."
    cp .env.example .env
fi

# Generate APP_KEY kalau kosong
if ! grep -q "^APP_KEY=" .env || [ -z "$(grep '^APP_KEY=' .env | cut -d= -f2-)" ]; then
    echo "🔑 Generating new APP_KEY..."
    php artisan key:generate --no-interaction --force
fi

# Jalankan migrasi
echo "🧱 Running migrations..."
php artisan migrate --force || echo "⚠️ Migration failed or no new migrations."

# Cache config dan routes
echo "⚙️  Caching Laravel configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permission ulang (biar ga 403)
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
chown -R www-data:www-data /var/www/html

# Pastikan Apache dengerin port Railway
echo "✅ Laravel ready! Serving on port ${PORT:-8080}..."
sed -i "s/Listen 80/Listen ${PORT:-8080}/" /etc/apache2/ports.conf

# Start Apache
exec apache2-foreground
