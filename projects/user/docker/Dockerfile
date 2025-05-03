FROM composer:2.7 AS composer

FROM php:8.3-fpm-alpine AS app

RUN apk add --no-cache \
      oniguruma-dev \
      postgresql-dev \
      libzip-dev \
      icu-dev zlib-dev git

RUN docker-php-ext-install intl mbstring pdo pdo_pgsql zip

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --no-interaction --prefer-dist --optimize-autoloader \
    && php artisan key:generate \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
