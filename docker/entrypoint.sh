#!/bin/bash
set -e

cd /var/www/html

# Install PHP dependencies
composer install --no-interaction --no-progress

# Install and build frontend assets
npm install
npm run build

# Generate app key if not set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
    php artisan key:generate --force
fi

# Wait for MySQL to be ready
echo "Waiting for MySQL..."
until php artisan migrate:status > /dev/null 2>&1; do
    sleep 2
done
echo "MySQL is ready."

# Run migrations and seed
php artisan migrate --force --seed

echo ""
echo "========================================="
echo " Interview App is running!"
echo " Visit: http://localhost:8000"
echo " Login: admin@example.com / password"
echo "========================================="
echo ""

# Start the development server
php artisan serve --host=0.0.0.0 --port=8000
