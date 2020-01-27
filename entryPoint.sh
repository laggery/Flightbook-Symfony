#!/bin/bash
set -xe

composer install

rm -R /usr/local/bin/composer
rm /var/www/html/composer-installer.sh

php bin/console cache:clear --env=prod --no-debug
chmod -R 777 /var/www/html/var

exec "$@"