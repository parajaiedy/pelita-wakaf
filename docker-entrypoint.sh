#!/usr/bin/env bash
set -e

cd /var/www/html || true

# Clear old caches
rm -rf storage/framework/views/* storage/framework/cache/* bootstrap/cache/*.php 2>/dev/null || true

# Run Laravel commands (best effort)
php artisan config:cache 2>/dev/null || true
php artisan route:cache 2>/dev/null || true
php artisan view:cache 2>/dev/null || true
php artisan migrate --force 2>/dev/null || true

# Ensure Apache listens on 0.0.0.0:80
grep -q "Listen 0.0.0.0:80" /etc/apache2/ports.conf || echo "Listen 0.0.0.0:80" >> /etc/apache2/ports.conf

# Start Apache foreground
exec apache2-foreground
