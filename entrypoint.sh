#!/bin/sh

echo "=== START ==="
echo "APP_KEY present: $([ -n "$APP_KEY" ] && echo YES || echo NO)"

chmod -R 777 storage bootstrap/cache

echo "=== Cache ==="
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "=== Migrate ==="
php artisan migrate --force 2>&1

echo "=== Seed ==="
php artisan db:seed --force 2>&1

echo "=== Starting server ==="
exec php -S 0.0.0.0:10000 -t public 2>&1