FROM php:8.0-fpm
COPY --from=composer:2.0 /usr/bin/composer /usr/local/bin/composer
ENV XDEBUG_VERSION="3.0.3"

RUN apt update && apt install -y \
    git \
    libzip-dev \
    unzip \
    libpq-dev \
    && apt clean

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && \
    docker-php-ext-install zip pdo pdo_pgsql pgsql

RUN mkdir /tmp/build-xdebug \
    && cd /tmp/build-xdebug \
    && curl -L -sS https://github.com/xdebug/xdebug/archive/$XDEBUG_VERSION.tar.gz -o xdebug.tar.gz \
    && tar -xzf xdebug.tar.gz \
    && rm xdebug.tar.gz \
    && cd xdebug-$XDEBUG_VERSION \
    && phpize \
    && ./configure --enable-xdebug \
    && make \
    && make install \
    && cd / \
    && rm -fr /tmp/build-xdebug \
    && docker-php-ext-enable xdebug

COPY xdebug3.ini /usr/local/etc/php/conf.d/xdebug.ini


WORKDIR /app
