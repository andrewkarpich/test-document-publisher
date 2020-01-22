#!/bin/bash

if [ ! -d vendor ]
then
    composer install --prefer-dist --no-interaction
else
    composer update --prefer-dist --no-interaction
fi

vendor/bin/phinx migrate -e default

php src/main.php