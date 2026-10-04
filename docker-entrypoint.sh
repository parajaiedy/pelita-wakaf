#!/usr/bin/env bash
set -e

# Cache and migrate
cd /var/www/html || true
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true
php artisan migrate --force || true

# Start Apache
exec apache2-foreground
