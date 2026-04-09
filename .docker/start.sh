#!/bin/bash
set -e

# Start PHP-FPM
php-fpm -D

# Wait for PHP-FPM to start
sleep 2

# Start Nginx
nginx -g 'daemon off;'