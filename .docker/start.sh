#!/bin/sh
php-fpm &
sleep 3

cat > /var/www/html/.env << EOF
APP_NAME="${APP_NAME}"
APP_ENV="${APP_ENV}"
APP_KEY="${APP_KEY}"
APP_DEBUG="${APP_DEBUG}"
APP_URL="${APP_URL}"
DB_CONNECTION="${DB_CONNECTION}"
DB_HOST="${DB_HOST}"
DB_PORT="${DB_PORT}"
DB_DATABASE="${DB_DATABASE}"
DB_USERNAME="${DB_USERNAME}"
DB_PASSWORD="${DB_PASSWORD}"
SESSION_DRIVER="${SESSION_DRIVER}"
SESSION_LIFETIME="${SESSION_LIFETIME}"
SESSION_SECURE_COOKIE=false
SESSION_SAME_SITE=none
EOF

mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/framework/views
chmod -R 777 /var/www/html/storage
chown -R www-data:www-data /var/www/html/storage
php artisan config:clear
php artisan cache:clear
php artisan migrate --force
php artisan db:seed --force
nginx -g "daemon off;" 2>&1