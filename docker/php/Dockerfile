ARG composer_version
ARG php_version

FROM php:${php_version}

WORKDIR /var/www/html

RUN apt-get update

# Dependencias de las extensiones PHP
RUN apt-get install -y libzip-dev libxml2-dev libpng-dev libfreetype6-dev libjpeg62-turbo-dev libxslt-dev libffi-dev musl-dev build-essential default-mysql-client

# Extensiones PHP requeridas
RUN docker-php-ext-install pdo pdo_mysql mysqli zip 
# xml iconv simplexml gd
# RUN docker-php-ext-configure gd --with-freetype --with-jpeg && docker-php-ext-install -j$(nproc) gd

RUN chown -R www-data:www-data /var/www/html

COPY --from=composer:2.1 /usr/bin/composer /usr/local/bin/composer
# Change current user to www
# USER www-data