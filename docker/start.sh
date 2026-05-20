#!/bin/sh
set -e

echo "🚀 Démarrage MyDocuHub..."

# Générer APP_KEY si absente
if [ -z "$APP_KEY" ]; then
    echo "⚠️  APP_KEY manquante — génération en cours..."
    php artisan key:generate --force
fi

# Migrations
echo "📦 Migrations..."
php artisan migrate --force

# Seeders
echo "🌱 Seeders..."
php artisan db:seed --class=CategorieSeeder --force
php artisan db:seed --class=FormateurSeeder --force

# Cache Laravel
echo "⚡ Cache..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Démarrage PHP-FPM en arrière-plan
echo "🔧 Démarrage PHP-FPM..."
php-fpm -D

# Démarrage Nginx au premier plan
echo "🌐 Démarrage Nginx sur le port 8080..."
nginx -g "daemon off;"
