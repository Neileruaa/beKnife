FROM php:7.4-apache

RUN apt-get update;apt-get install -y git zip unzip apt-utils wkhtmltopdf
RUN docker-php-ext-install pdo_mysql
COPY ./docker/php/php.ini /usr/local/etc/php/
COPY ./docker/apache/default.conf /etc/apache2/sites-available/000-default.conf
COPY . /var/www/html/
RUN mkdir -p /var/www/html/public/uploads/annales && chmod -R 777 /var/www/html/public/uploads/annales
RUN mkdir -p /var/www/.cache && chown www-data /var/www/.cache && chgrp www-data /var/www/.cache
RUN mkdir -p /var/www/.config && chown www-data /var/www/.config && chgrp www-data /var/www/.config

WORKDIR /var/www/html

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === '906a84df04cea2aa72f40b5f787e49f22d4c2f19492ac310e8cba5b96ac8b64115ac402c8cd292b8a03482574915d1a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"

RUN mv composer.phar /usr/local/bin/composer

#RUN rm -rf /var/www/html/var/cache/*; chmod -R 777 /var/www/html/var; chmod -R 777 /var/www/html/data



#RUN rm -rf /var/www/html/var/cache/*; chmod -R 777 /var/www/html/var; chmod -R 777 /var/www/html/data
EXPOSE 80