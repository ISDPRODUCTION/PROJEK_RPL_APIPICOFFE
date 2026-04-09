#!/bin/sh
php-fpm &
sleep 3
php artisan config:clear
php artisan cache:clear
php artisan session:table 2>/dev/null || true
php artisan migrate --force
nginx -g "daemon off;" 2>&1