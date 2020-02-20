#!/bin/sh

bash /wait-for-it.sh $DB_HOST:$DB_PORT -t 30

# TODO this should be moved to dockerfile (but it didn't work, was problem with variable PHP_PORT)
sed -i "s/9000/$PHP_PORT/" /usr/local/etc/php-fpm.d/zz-docker.conf
sed -i "s/max_execution_time = 30/max_execution_time = $PHP_MAX_EXECUTION_TIME/" /usr/local/etc/php/php.ini-production
sed -i "s/max_execution_time = 30/max_execution_time = $PHP_MAX_EXECUTION_TIME/" /usr/local/etc/php/php.ini-development

docker-php-entrypoint

php-fpm
