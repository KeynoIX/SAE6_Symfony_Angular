FROM php:8.2-fpm-alpine

# Installation des dépendances système
RUN apk add --no-cache \
    git \
    unzip \
    libzip-dev \
    icu-dev

# Installation des extensions PHP individuellement 
RUN docker-php-ext-install ctype && \
    docker-php-ext-install pdo && \
    docker-php-ext-install pdo_mysql && \
    docker-php-ext-install zip && \
    docker-php-ext-install intl

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Exposition du port pour PHP-FPM
EXPOSE 9000

# Script de démarrage pour installer les dépendances si nécessaire
CMD composer install --no-interaction --optimize-autoloader && php-fpm