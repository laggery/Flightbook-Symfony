#!/bin/sh
set -xe

php /home/composer/composer run-script post-install-cmd

rm -R /home/composer

php bin/console cache:clear --env=prod --no-debug
chmod -R 777 /var/www/html/var

exec "$@"