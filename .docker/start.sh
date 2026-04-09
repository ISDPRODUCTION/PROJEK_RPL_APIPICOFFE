#!/bin/sh

# Start PHP-FPM in background
php-fpm -D

# Wait for PHP-FPM
sleep 2

# Test nginx config
nginx -t

# Start Nginx in foreground
exec nginx -g 'daemon off;'