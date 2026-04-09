#!/bin/sh
echo "Starting PHP-FPM..."
php-fpm &
sleep 3
echo "Running migrations..."
php artisan migrate --force
echo "Starting Nginx..."
nginx -g "daemon off;" 2>&1