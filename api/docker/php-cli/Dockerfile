FROM php:7.4-cli-alpine

RUN apk add --no-cache postgresql-dev bash coreutils \
	&& docker-php-ext-configure pgsql --with-pgsql=/usr/local/pgsql \
	&& docker-php-ext-install pdo_pgsql

RUN apk add --no-cache unzip

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

COPY ./conf.d /usr/local/etc/php/conf.d
COPY ./wait-for-it.sh /usr/local/bin/wait-for-it
RUN chmod +x /usr/local/bin/wait-for-it

ENV COMPOSER_ALLOW_SUPERUSER 1

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer --quiet

WORKDIR /app
