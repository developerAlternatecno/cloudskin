ARG composer_version
ARG php_version

FROM php:${php_version}

WORKDIR /var/www/html

RUN apt-get update && \
    apt-get install -y \
        libzip-dev \
        libxml2-dev \
        libpng-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libxslt-dev \
        libffi-dev \
        musl-dev \
        build-essential \
        # supervisor \
        default-mysql-client &&\
        # python3 \
        # python3-pip \
        # python3-pandas \
        # python3-requests && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Extensiones PHP requeridas
RUN docker-php-ext-install pdo pdo_mysql mysqli zip

# Instalación de la extensión gd
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd

# Configuración de Xdebug para la depuración remota
RUN pecl install xdebug-3.1.5 && \
   docker-php-ext-enable xdebug

ADD xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Cambiar el propietario del directorio
RUN chown -R www-data:www-data /var/www/html

# Copiar el script de Python al contenedor
#COPY -r /var/www/python /var/www/html/python/

COPY --from=composer:2.1 /usr/bin/composer /usr/local/bin/composer
# Change current user to www
# USER www-data