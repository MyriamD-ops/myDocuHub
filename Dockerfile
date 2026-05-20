FROM php:8.2-apache

# Installation des dépendances système nécessaires
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl

# Installation des extensions PHP requises par Laravel
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Activation du module de réécriture d'URL Apache
RUN a2enmod rewrite

# Configuration du dossier pointant vers le répertoire "public" de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Récupération de l'outil Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copie des fichiers du projet
WORKDIR /var/www/html
COPY . .

# Installation des paquets PHP
RUN composer install --no-dev --optimize-autoloader

# Ajustement des permissions pour les dossiers de cache et stockage Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80