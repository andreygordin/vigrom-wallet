FROM php:7.4-fpm-alpine

RUN apk add --no-cache postgresql-dev \
	&& docker-php-ext-configure pgsql --with-pgsql=/usr/local/pgsql \
	&& docker-php-ext-install pdo_pgsql

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

COPY ./conf.d /usr/local/etc/php/conf.d

WORKDIR /app
