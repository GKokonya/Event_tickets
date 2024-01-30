#install debin
FROM debian:12-slim

ARG HOST_UID=1000
ENV USER=www-data
ARG WORKDIR=/var/www/html

# Set working directory
WORKDIR $WORKDIR

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

#install supervisor
RUN apt-get install supervisor -y  

# Copy the Supervisor configuration file into the container
COPY docker/php/supervisor/laravel-worker.conf /etc/supervisor/conf.d/laravel-worker.conf


#install nginx
RUN apt-get install nginx=1.22.1-9 -y

#prevent automatic upgrade of nginx
RUN apt-mark hold nginx

#install firewall
RUN apt install ufw -y

#allow http in firewall
RUN ufw allow http

#allow https in firewall
RUN ufw allow https

#install php extensions
RUN apt-get install -y php8.2 php8.2-fpm \
    php8.2-common php8.2-zip php8.2-curl php8.2-xml php8.2-xmlrpc \
    #php8.2-json \
     php8.2-mysql php8.2-pdo php8.2-gd php8.2-imagick php8.2-ldap php8.2-imap php8.2-mbstring \
    php8.2-intl php8.2-cli php8.2-tidy php8.2-bcmath php8.2-opcache \
    php8.2-cli php8.2-dev php8.2-imap php8.2-soap php8.2-readline \
    php8.2-msgpack php8.2-igbinary php8.2-redis \
    #php8.2-swoole \
    php8.2-memcached php8.2-pcov php8.2-xdebug 

#get 21.1.4 version of node
RUN curl -sL https://deb.nodesource.com/setup_21.5.0

#install node js
RUN apt-get install nodejs -y

#prevent automatic upgrade of node js
RUN apt-mark hold nodejs

#install node package manager
RUN apt-get install npm -y

#
RUN  npm install -g npm@9.2.0 

# COPY package.json ./
# COPY package-lock.json ./

# RUN pecl install -o -f redis-7.2.3

# RUN docker-php-ext-enable redis

#install composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

#create a user called app user that maps os user to container user
RUN usermod -u ${HOST_UID} www-data
RUN groupmod -g ${HOST_UID} www-data

RUN chmod -R 755 $WORKDIR
RUN chown -R www-data:www-data $WORKDIR

EXPOSE 80 
EXPOSE 5173


#Immportant
ENTRYPOINT ["sh","./docker/nginx/start.sh"]



