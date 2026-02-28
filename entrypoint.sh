#!/bin/sh

# Clear & cache config au démarrage
php artisan config:clear
php artisan cache:clear

# Start Laravel
php artisan serve --host=0.0.0.0 --port=10000