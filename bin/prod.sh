#!/bin/bash

clear
rm storage/logs/*.log
rm storage/mailer/*.html
composer dump-env prod
composer cc
composer install --no-dev --optimize-autoloader
APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear

zip -r App.zip . -x '.git/*' -x '.idea/*' -x 'src/Command/*' -x '*.env' -x '*.env.*' -x 'CHANGELOG.md' -x 'bin/*.sh' -x 'LICENSE' -x 'README.md' -x 'SECURITY.md' -x 'todo.md'

echo 'Done!'
