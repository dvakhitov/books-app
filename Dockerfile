FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libzip-dev libonig-dev zip \
    && docker-php-ext-install intl pdo_mysql zip opcache \
    && docker-php-ext-enable intl pdo_mysql zip opcache

WORKDIR /var/www/html

COPY ./src /var/www/html
RUN chown -R www-data:www-data /var/www/html
