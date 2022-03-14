# PHP
FROM php:5.6-fpm

RUN apt-get update && apt-get install -y \
    libjpeg62-turbo-dev \
    libpng-dev \
    git \
    zip \
    && docker-php-ext-configure gd  \
    && docker-php-ext-install gd

RUN echo 'date.timezone = Europe/Moscow' > /usr/local/etc/php/conf.d/docker-php-timezone.ini
RUN rm /etc/localtime
RUN ln -s /usr/share/zoneinfo/Europe/Moscow /etc/localtime

COPY ./build/php/www.conf /usr/local/etc/php-fpm.d/www.conf
RUN rm -f /usr/local/etc/php-fpm.d/zz-docker.conf

WORKDIR /var/www/html

#RUN curl -s https://raw.githubusercontent.com/composer/getcomposer.org/76a7060ccb93902cd7576b67264ad91c8a2700e2/web/installer|php --
#RUN ./composer.phar install
