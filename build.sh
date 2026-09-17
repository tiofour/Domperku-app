#!/usr/bin/env bash
# Hentikan eksekusi jika terjadi kesalahan
set -e

echo "=== Mula Instalasi Dependensi Composer ==="
composer install --no-dev --optimize-autoloader

echo "=== Mengoptimalkan Cache Laravel ==="
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "=== Menjalankan Migrasi Database Aiven ==="
php artisan migrate --force

echo "=== Proses Build Selesai! ==="