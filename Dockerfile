# syntax=docker/dockerfile:1.7

# ── Stage 1: composer dependencies ────────────────────────────────────────────
FROM composer:2 AS vendor

RUN apk add --no-cache icu-dev && docker-php-ext-install intl

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --prefer-dist \
    --no-interaction \
    --no-progress

# ── Stage 2: frontend assets (PHP + Node) ─────────────────────────────────────
# php:8.4-cli-alpine is the base so install-php-extensions works correctly.
# Node 24 is copied in from node:24-alpine (same Alpine version — compatible binaries).
FROM php:8.4-cli-alpine AS assets

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN install-php-extensions pdo_sqlite mbstring xml intl curl tokenizer bcmath

COPY --from=node:24-alpine /usr/local/bin/node /usr/local/bin/node
COPY --from=node:24-alpine /usr/local/lib/node_modules/npm /usr/local/lib/node_modules/npm
RUN ln -sf /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm \
    && ln -sf /usr/local/lib/node_modules/npm/bin/npx-cli.js /usr/local/bin/npx

WORKDIR /app
COPY package.json package-lock.json* ./
RUN npm ci

COPY . .
COPY --from=vendor /app/vendor ./vendor

# Generate autoloader (vendor stage skipped it), create required Laravel dirs,
# inject a temporary APP_KEY so artisan can boot during the Vite build.
RUN composer dump-autoload --no-scripts --no-interaction \
    && mkdir -p bootstrap/cache \
        storage/framework/cache \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
    && cp .env.example .env \
    && php artisan key:generate --ansi \
    && npm run build \
    && rm .env

# ── Stage 3: runtime ──────────────────────────────────────────────────────────
FROM php:8.4-fpm-alpine AS runtime

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN apk add --no-cache git curl zip unzip bash supervisor \
    && mkdir -p /etc/supervisor/conf.d \
    && install-php-extensions \
        pdo_mysql mbstring exif pcntl bcmath gd zip intl opcache redis

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY --chown=www-data:www-data . /var/www/html
COPY --from=vendor --chown=www-data:www-data /app/vendor /var/www/html/vendor
COPY --from=assets --chown=www-data:www-data /app/public/build /var/www/html/public/build

RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && composer dump-autoload --optimize --classmap-authoritative

COPY docker/php/php.ini /usr/local/etc/php/conf.d/99-app.ini
COPY docker/php/fpm-pool.conf /usr/local/etc/php-fpm.d/www.conf
COPY docker/php/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/php/start-container.sh /usr/local/bin/start-container
RUN chmod +x /usr/local/bin/start-container

EXPOSE 9000

ENTRYPOINT ["start-container"]
