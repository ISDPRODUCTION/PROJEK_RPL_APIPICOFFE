#!/bin/sh
php-fpm &
sleep 3
php artisan config:clear
php artisan cache:clear
chmod -R 777 /var/www/html/storage
php artisan migrate --force
php artisan key:generate --force
nginx -g "daemon off;" 2>&1