#!/bin/sh
php-fpm &
sleep 3
nginx -g "daemon off;"