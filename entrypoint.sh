#!/bin/sh
set -e  # Arrête le script si une commande échoue

echo "=== START ==="
echo "APP_KEY present: $([ -n "$APP_KEY" ] && echo YES || echo NO)"

# Permissions plus sécurisées
chmod -R 775 storage bootstrap/cache

# Installation des dépendances PHP (déjà fait au build, mais sécurise)
echo "=== Composer install ==="
composer install --no-dev --optimize-autoloader 2>&1

# Installation des dépendances Node et build Vite
if [ -f "package.json" ]; then
    echo "=== Installing Node dependencies ==="
    npm ci 2>&1  # Installe toutes les dépendances (y compris dev)
    echo "=== Building Vite assets ==="
    npm run build 2>&1
fi

echo "=== Storage link ==="
php artisan storage:link 2>&1

echo "=== Cache ==="
php artisan route:cache 2>&1   # Optionnel en développement
php artisan view:cache 2>&1

echo "=== Migrate ==="
php artisan migrate --force 2>&1
php artisan db:seed --force 2>&1   # Assure-toi que le seeder est idempotent

echo "=== Starting server ==="
exec php -S 0.0.0.0:10000 -t public 2>&1