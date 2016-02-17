FROM php:5.6-cli

RUN apt-get update \
    && apt-get install -y git curl bzip2 libssl-dev zlib1g-dev libicu-dev nano \
    && docker-php-ext-install zip mbstring intl opcache

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/bin/composer

WORKDIR /home/wirus/enum
