#!/bin/bash
set -e

cd /var/www/html

# Use Docker-specific env config
cp .env.docker .env

# Install PHP dependencies
composer install --no-interaction --no-progress

# Install frontend dependencies
npm install

# Generate app key if not set
if grep -q "^APP_KEY=$" .env; then
    php artisan key:generate --force
fi

# Run migrations
php artisan migrate --force

# Seed only if database is empty
USER_COUNT=$(php artisan tinker --execute="echo App\Models\User::count();" 2>/dev/null)
if [ "$USER_COUNT" = "0" ]; then
    php artisan db:seed --force
fi

# Start Vite dev server in background (hot-reloads CSS/JS changes)
npm run dev &

echo ""
echo "========================================="
echo " Interview App is running!"
echo " Visit: http://localhost:8000"
echo " Login: admin@example.com / password"
echo "========================================="
echo ""

# Start PHP dev server
php artisan serve --host=0.0.0.0 --port=8000
