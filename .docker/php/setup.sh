#!/bin/bash
cd /var/www/html
composer install
bin/console doctrine:migrations:migrate --no-interaction
bin/console doctrine:fixtures:load --no-interaction --quiet
bin/console doctrine:migrations:migrate --no-interaction --env=test
bin/console doctrine:fixtures:load --no-interaction --quiet --env=test
php-fpm
