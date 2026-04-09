#!/bin/sh

# Start PHP-FPM in foreground mode in background
php-fpm &

# Wait longer for PHP-FPM to fully start
sleep 5

# Start Nginx in foreground
exec nginx -g 'daemon off;'