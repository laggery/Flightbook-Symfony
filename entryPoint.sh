#!/bin/bash
set -xe

composer install

rm -f /var/www/html/composer-installer.sh

php bin/console cache:clear --env=prod --no-debug
chmod -R 777 /var/www/html/var

exec "$@"