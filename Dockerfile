FROM jaderh/docker-nginx-php-supervisor:mt as mt-php

LABEL maintainer="Jade <hmy940118@gmail.com>"

WORKDIR /webser/www/mt-backend

COPY ./ ./

COPY ./mt-backend.conf /etc/nginx/conf.d/mt-backend.conf

RUN cp ./.env.test ./.env
RUN composer install

RUN chown -R www-data:www-data /webser/www
