#!/bin/bash
set -e

echo "🚀 Starting Laravel container..."

if [ ! -f .env ]; then
    echo "📄 .env not found, creating from example..."
    cp .env.example .env
fi

# Generate APP_KEY kalau belum ada atau kosong
if ! grep -q "^APP_KEY=" .env || [ -z "$(grep '^APP_KEY=' .env | cut -d= -f2-)" ]; then
    echo "🔑 Generating new APP_KEY..."
    KEY=$(php -r "echo 'base64:'.base64_encode(random_bytes(32));")
    sed -i "/^APP_KEY=/d" .env
    echo "APP_KEY=${KEY}" >> .env
    echo "✅ APP_KEY set to: ${KEY}"
fi

echo "🧱 Running migrations..."
php artisan migrate --force || echo "⚠️ Migration failed or no new migrations."

echo "⚙️  Caching Laravel configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
chown -R www-data:www-data /var/www/html

echo "✅ Laravel ready! Serving on port ${PORT:-8080}..."
sed -i "s/Listen 80/Listen ${PORT:-8080}/" /etc/apache2/ports.conf

exec apache2-foreground
