#!/bin/bash
set -e

echo "Starting Container"

# Jalankan migration (optional, bisa dihapus kalau gak mau otomatis migrate)
echo "Running migrations..."
php artisan migrate --force || true

# Cache optimizations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start Apache
echo "Starting Apache on port ${PORT:-8080}..."
exec apache2-foreground
