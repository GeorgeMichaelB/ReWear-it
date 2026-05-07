#!/bin/bash
set -e

echo "Running migrations and seeding..."
php artisan config:clear
php artisan migrate:fresh --force --seed

echo "Starting Laravel server..."
php artisan serve --host=0.0.0.0 --port=8000