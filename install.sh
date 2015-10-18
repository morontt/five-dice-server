#!/usr/bin/env bash

if type composer &> /dev/null; then
    composer install --optimize-autoloader
else
    if [ ! -f ./composer.phar ]; then
        curl -sS https://getcomposer.org/installer | php
    fi

    php composer.phar self-update
    php composer.phar install --optimize-autoloader
fi

if [ ! -f ./database/game.db3 ]; then
    chmod a+w database
    touch database/game.db3
    chmod a+w database/game.db3

    ./console db:schema
fi
