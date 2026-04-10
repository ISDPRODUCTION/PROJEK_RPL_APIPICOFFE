#!/bin/sh
php-fpm &
sleep 3

# Buat .env dari environment variables Railway
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
EOF

php artisan config:clear
php artisan cache:clear
chmod -R 777 /var/www/html/storage
php artisan migrate --force
nginx -g "daemon off;" 2>&1
chmod -R 777 /var/www/html/storage/framework/sessions