FROM php:7-cli

RUN docker-php-ext-install \
    pdo_mysql

RUN usermod -u 1000 www-data

WORKDIR /opt/behat
