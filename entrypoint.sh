#!/bin/sh

echo "=== START ==="
echo "APP_KEY present: $([ -n "$APP_KEY" ] && echo YES || echo NO)"

chmod -R 777 storage bootstrap/cache

echo "=== About ==="
php artisan about 2>&1

echo "=== Migrate ==="
php artisan migrate --force 2>&1
php artisan db:seed --force 2>&1
composer install --force 2>&1


echo "=== Starting server ==="
exec php -S 0.0.0.0:10000 -t public 2>&1

 