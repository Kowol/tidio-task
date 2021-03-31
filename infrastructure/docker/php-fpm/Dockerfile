FROM php:8.0-fpm
COPY --from=composer:2.0 /usr/bin/composer /usr/local/bin/composer

RUN apt update && apt install -y \
    git \
    libzip-dev \
    unzip \
    && apt clean

RUN docker-php-ext-install zip

WORKDIR /app
