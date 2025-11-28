#!/bin/bash

rm .env.local.php
rm App.zip
touch storage/mailer/mailer.html
composer cc
composer install -o && composer upgrade -o

echo 'Done!'
