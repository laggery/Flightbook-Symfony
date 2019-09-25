FROM php:7.2-apache

RUN apt-get update && \
	apt-get install -y zip unzip && \
    apt-get clean
	
RUN docker-php-ext-install pdo pdo_mysql


RUN apt-get install -y libwebp-dev libjpeg62-turbo-dev libpng-dev libxpm-dev \
    libfreetype6-dev curl
	
RUN docker-php-ext-configure gd --with-gd --with-webp-dir --with-jpeg-dir \
    --with-png-dir --with-zlib-dir --with-xpm-dir --with-freetype-dir
	
RUN docker-php-ext-install gd zip
	
	
RUN a2enmod rewrite

COPY . /var/www/html/

RUN curl -s https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

COPY virtual-host.conf /etc/apache2/sites-available/000-default.conf

## Copy the EntryPoint file
#COPY ./entryPoint.sh /

# Correct file format to linux standard
RUN sed -i -e 's/\r$//' /var/www/html/entryPoint.sh

## Edit file rights to run correctly at startup 
RUN chmod +x /var/www/html/entryPoint.sh

ENTRYPOINT ["sh", "/var/www/html/entryPoint.sh"]

CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]