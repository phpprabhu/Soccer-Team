FROM php:7.2.5-apache-stretch

RUN a2enmod rewrite

MAINTAINER Prabhu(phpprabhu@gmail.com)

RUN curl --insecure https://getcomposer.org/composer.phar -o /usr/bin/composer && chmod +x /usr/bin/composer

RUN apt-get update \
    && apt-get install -y git zip unzip \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY 000-default.conf /etc/apache2/sites-available/000-default.conf


ARG USER_ID

RUN usermod -u $USER_ID www-data

RUN chown -R www-data:www-data /var/www/html

RUN docker-php-ext-install pdo pdo_mysql

RUN chown -R www-data:www-data /var/www/
