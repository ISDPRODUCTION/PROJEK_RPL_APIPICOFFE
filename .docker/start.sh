#!/bin/sh
php-fpm &
sleep 3
php artisan config:clear
php artisan cache:clear
chmod -R 777 /var/www/html/storage
php artisan migrate --force
nginx -g "daemon off;" 2>&1