#!/usr/bin/env bash
# exit on error
set -o errexit

composer install --no-dev --optimize-autoloader

# Menjalankan migrasi database di Aiven
php artisan migrate --force
