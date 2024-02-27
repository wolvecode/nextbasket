FROM php:8.2.16-fpm-bullseye

RUN apt-get update && docker-php-ext-install sockets && docker-php-ext-enable sockets
RUN apt-get install -y \
  libzip-dev \
  zip \
  unzip \
  && docker-php-ext-install zip \
  && docker-php-source delete

COPY ./services/users /app/nextbasket/services/users

COPY ./lib/ /app/nextbasket/lib/

WORKDIR /app/nextbasket/services/users

RUN curl https://getcomposer.org/download/2.7.1/composer.phar --output composer

RUN chmod +x composer

RUN ./composer update && ./composer install

EXPOSE 8000

ENTRYPOINT ["php", "-S", "0.0.0.0:8000", "-t", "./public"]
