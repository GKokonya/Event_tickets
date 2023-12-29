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

#install git
RUN apt-get install git -y  

#install nginx
#RUN apt-get install nginx=1.22.1-9 -y

#prevent automatic upgrade of nginx
#RUN apt-mark hold nginx

#get 21.1.4 version of node
# RUN curl -sL https://deb.nodesource.com/setup_21.5.0

# #install node js
# RUN apt-get install nodejs -y

#prevent automatic upgrade of node js
# RUN apt-mark hold nodejs

#install node package manager
# RUN apt-get install npm -y

COPY package.json ./
COPY package-lock.json ./

# RUN  npm install -g npm@9.2.0 

#EXPOSE 3000

RUN  docker-php-ext-install zip pdo pdo_mysql 

RUN pecl install -o -f redis-7.2.3

RUN docker-php-ext-enable redis

#install composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

#create a user called app user that maps os user to container user
RUN groupadd --gid 1000 appuser \
    && useradd --uid 1000 -g appuser \
        -G www-data,root --shell /bin/bash \
        --create-home appuser

USER appuser