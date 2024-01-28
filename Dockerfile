FROM php:8.2-fpm-alpine

RUN apk update && apk add --no-cache nginx curl libpng-dev libzip-dev

WORKDIR /app

COPY . /app

# install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === 'e21205b207c3ff031906575712edab6f13eb0b361f2085f1f1237b7126d785e826a450292b6cfd1d64d92e6563bbde02') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer

# install php extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql gd zip
RUN docker-php-ext-enable mysqli pdo pdo_mysql gd zip

# install npm
RUN apk add --update npm

WORKDIR /app/src

EXPOSE 80

CMD ["php", "artisan", "serve", "--host", "0.0.0.0", "--port", "80"]