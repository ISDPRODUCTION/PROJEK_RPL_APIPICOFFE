#!/bin/sh
echo "Starting PHP-FPM..."
php-fpm &
sleep 3
echo "Clearing config cache..."
php artisan config:clear
echo "Running migrations..."
php artisan migrate --force
echo "Starting Nginx..."
nginx -g "daemon off;" 2>&1