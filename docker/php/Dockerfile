FROM php:8.1-fpm-alpine

LABEL Maintainer="Shane Gunawardana <shanechioguna@gmail.com>"

# environment args
ARG UID
ARG GID
ARG USER

ENV UID=${UID}
ENV GID=${GID}
ENV USER=${USER}

RUN addgroup -g ${GID} --system ${USER}
RUN adduser -G ${USER} --system -D -s /bin/sh -u ${UID} ${USER}

RUN sed -i "s/user = www-data/user = '${USER}'/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i "s/group = www-data/group = '${USER}'/g" /usr/local/etc/php-fpm.d/www.conf
RUN echo "php_admin_flag[log_errors] = on" >> /usr/local/etc/php-fpm.d/www.conf

RUN apk update && apk add \
    build-base \
    git \
    curl \
    zip \
    libzip-dev \
    libpq-dev \
    postgresql \
    postgresql-client \
    unzip \
    nano \
    php-pgsql \
    php-pdo

RUN set -ex \
    && apk --no-cache add \
    postgresql-dev

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql
RUN docker-php-ext-install pdo pdo_pgsql pgsql zip exif pcntl

#COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
#RUN composer install --no-interaction
#
#RUN php artisan key:generate

CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]
