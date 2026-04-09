#!/bin/sh

mkdir -p /run/php

# Start PHP-FPM
php-fpm &

# Wait for socket to be created
while [ ! -S /run/php/php8.3-fpm.sock ]; do
    sleep 1
done

echo "PHP-FPM socket ready"

# Start Nginx
exec nginx -g 'daemon off;'