#!/bin/bash

rm storage/logs/*.log
echo "" > storage/mailer/mailer.html
php bin/console cache:clear
composer cc && composer upgrade -o
php -S localhost:8080 -t public
