#!/bin/sh

# Start PHP-FPM
php-fpm &

# Wait and find the socket
sleep 3
echo "Looking for PHP-FPM socket..."
find /run /var/run -name "*.sock" 2>/dev/null
ls -la /run/php/ 2>/dev/null || echo "No /run/php directory"

# Start Nginx regardless
exec nginx -g 'daemon off;'j