#!/bin/sh

echo "Clearing config..."
php artisan config:clear
php artisan cache:clear

echo "Running migrations..."
php artisan migrate --force

echo "Starting Laravel..."
php artisan serve --host=0.0.0.0 --port=10000