FROM php:8.2.16-fpm-bullseye

RUN apt-get update && docker-php-ext-install sockets && docker-php-ext-enable sockets \
      && docker-php-source delete

COPY ./services/notifications /app/nextbasket/services/notifications

COPY ./lib/ /app/nextbasket/lib/

WORKDIR /app/nextbasket/services/notifications

RUN curl https://getcomposer.org/download/2.7.1/composer.phar --output composer

RUN chmod +x composer

RUN ./composer update && ./composer install

ENTRYPOINT ["sleep", "10", "&&", "php", "src/index.php"]
