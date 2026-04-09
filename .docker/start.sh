#!/bin/sh
echo "PORT is: $PORT"
echo "Starting PHP-FPM..."
php-fpm &
sleep 3
echo "Starting Nginx on port $PORT..."
envsubst '$PORT' < /etc/nginx/sites-available/default > /etc/nginx/sites-enabled/default
nginx -g "daemon off;" 2>&1