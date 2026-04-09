#!/bin/sh
echo "Starting PHP-FPM..."
php-fpm &
sleep 3
echo "Starting Nginx..."
nginx -g "daemon off;" 2>&1