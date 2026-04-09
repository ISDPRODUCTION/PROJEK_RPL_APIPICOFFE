#!/bin/sh
echo "Starting PHP-FPM..."
php-fpm &
FPM_PID=$!
echo "PHP-FPM started with PID $FPM_PID"
sleep 3
echo "Starting Nginx..."
nginx -g "daemon off;"
echo "Nginx exit code: $?"