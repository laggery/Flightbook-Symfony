FROM php:7.2-apache

RUN apt-get update && \
	apt-get install -y zip unzip && \
    apt-get clean
	
RUN docker-php-ext-install pdo pdo_mysql


RUN apt-get install -y libwebp-dev libjpeg62-turbo-dev libpng-dev libxpm-dev \
    libfreetype6-dev
	
RUN docker-php-ext-configure gd --with-gd --with-webp-dir --with-jpeg-dir \
    --with-png-dir --with-zlib-dir --with-xpm-dir --with-freetype-dir
	
RUN docker-php-ext-install gd zip
	
	
RUN a2enmod rewrite

COPY . /var/www/html/

RUN mkdir /home/composer
RUN php -r "copy('https://getcomposer.org/installer', '/home/composer/composer-setup.php');"
RUN php /home/composer/composer-setup.php --install-dir=/home/composer/ --filename=composer
RUN php /home/composer/composer install --no-scripts

#RUN rm -R /home/composer

RUN chmod -R 0775 var
#RUN php bin/console cache:clear --env=dev --no-debug
#RUN php bin/console cache:clear --env=prod --no-debug

RUN mv app/config/parameters.yml.dist app/config/parameters.yml
RUN mv app/config/parameters.php.dist app/config/parameters.php

COPY virtual-host.conf /etc/apache2/sites-available/000-default.conf

RUN chmod 755 /var/www/html/docker-entrypoint.sh
ENTRYPOINT ["/var/www/html/docker-entrypoint.sh"]
