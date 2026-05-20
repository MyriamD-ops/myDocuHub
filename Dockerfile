FROM php:8.4-fpm-alpine

# ── Dépendances système ───────────────────────────────────────────────────────
RUN apk add --no-cache \
    nginx \
    nodejs \
    npm \
    git \
    curl \
    zip \
    unzip \
    libpq-dev \
    libzip-dev \
    oniguruma-dev \
    libxml2-dev \
    icu-dev \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        pgsql \
        mbstring \
        zip \
        bcmath \
        xml \
        intl \
    && rm -rf /var/cache/apk/*

# ── Composer ──────────────────────────────────────────────────────────────────
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ── Répertoire de travail ─────────────────────────────────────────────────────
WORKDIR /var/www/html

# ── Copie du code ─────────────────────────────────────────────────────────────
COPY . .

# ── Installation des dépendances PHP ─────────────────────────────────────────
RUN composer install --no-dev --optimize-autoloader --no-interaction

# ── Installation des dépendances JS + build assets ───────────────────────────
RUN rm -f package-lock.json && npm install && npm run build && rm -rf node_modules

# ── Permissions storage ───────────────────────────────────────────────────────
RUN mkdir -p storage/app/private/documents \
    bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# ── Configuration Nginx ───────────────────────────────────────────────────────
COPY docker/nginx.conf /etc/nginx/nginx.conf

# ── Script de démarrage ───────────────────────────────────────────────────────
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 8080

CMD ["/start.sh"]
