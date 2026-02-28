#!/bin/sh
set -e

echo "Setting permissions..."
chmod -R 775 storage bootstrap/cache

echo "Clearing cache..."
php artisan config:clear
php artisan cache:clear

echo "Running migrations..."
php artisan migrate --force

echo "Starting PHP server..."
exec php -S 0.0.0.0:10000 -t public