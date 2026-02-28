#!/bin/sh

echo "=== Permissions..."
chmod -R 777 storage bootstrap/cache

echo "=== Storage link..."
php artisan storage:link --force 2>&1

echo "=== Migrate..."
php artisan migrate --force 2>&1

echo "=== Starting server..."
exec php -S 0.0.0.0:10000 -t public 2>&1