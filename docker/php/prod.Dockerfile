FROM composer AS composer-uild


WORKDIR /var/www/html

COPY composer.json composer.lock /var/www/html/

RUN mkdir var/www/html/database{factories,seeders} \

#donwload docmposer dependencies
#1. --no-dev means we ignore the development dependencies,
#2--no-scripts means we don't want to execte any scripts
RUN composer install --no-dev --prefer-dist --no-scripts --no-autoloader --no-progress --ignore-platform -reqs

#Npm Dependecies
From node:21.5.0 AS npm-buiilder

WORKDIR /var/www/html

COPY package.json package.lock vite.config.js /var/www/html/
COPY resources /var/www/html/
COPY public /var/www/html/

#npm install
RUN npm ci

#run assets for production
RUN npm run production


#Actual production image

FROM php:8.3-fpm

WORKDIR /var/www/html 


#update os
RUN apt-get update

#install libzip-dev
RUN apt-get install libzip-dev -y

#install unzip
RUN apt-get install unzip -y

#install curl
RUN apt-get install curl -y


#get 21.1.4 version of node
RUN curl -sL https://deb.nodesource.com/setup_21.5.0

#install node js
RUN apt-get install nodejs -y

#prevent automatic upgrade of node js
RUN apt-mark hold nodejs

#install node package manager
RUN apt-get install npm -y

COPY package.json ./
COPY package-lock.json ./

RUN  npm install -g npm@9.2.0 

RUN  docker-php-ext-install zip pdo pdo_mysql 

RUN pecl install -o -f redis-7.2.3

RUN docker-php-ext-enable redis

#install composer
COPY --from=composer /usr/bin/composer /usr/bin/composer