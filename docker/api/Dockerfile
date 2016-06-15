FROM php:7-fpm

RUN docker-php-ext-install \
    pdo_mysql

RUN usermod -u 1000 www-data

WORKDIR /var/www/app

CMD ["php-fpm"]

EXPOSE 9000
