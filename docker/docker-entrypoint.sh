#!/bin/sh
set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- supervisord "$@"
fi

if [ "$SYMFONY_ENV" = "dev" ]; then
    composer install --no-scripts --no-interaction;
    php bin/console asset:install;
fi

mkdir -p var/cache var/log

bin/console doctrine:cache:clear-metadata --flush
bin/console doctrine:cache:clear-query --flush
bin/console cache:clear

bin/console doctrine:database:create --if-not-exists

echo "Waiting for db to be ready..."
ATTEMPTS_LEFT_TO_REACH_DATABASE=60
until [ $ATTEMPTS_LEFT_TO_REACH_DATABASE -eq 0 ] || DATABASE_ERROR=$(bin/console doctrine:query:sql "SELECT 1" 2>&1); do
    if [ $? -eq 255 ]; then
        # If the Doctrine command exits with 255, an unrecoverable error occurred
        ATTEMPTS_LEFT_TO_REACH_DATABASE=0
        break
    fi
    sleep 1
    ATTEMPTS_LEFT_TO_REACH_DATABASE=$((ATTEMPTS_LEFT_TO_REACH_DATABASE - 1))
    echo "Still waiting for db to be ready... Or maybe the db is not reachable. $ATTEMPTS_LEFT_TO_REACH_DATABASE attempts left"
done

if [ $ATTEMPTS_LEFT_TO_REACH_DATABASE -eq 0 ]; then
    echo "The database is not up or not reachable:"
    echo "$DATABASE_ERROR"
    exit 1
else
    echo "The db is now ready and reachable"
fi

if ls -A migrations/*.php >/dev/null 2>&1; then
    bin/console doctrine:migrations:migrate --no-interaction
fi

exec "$@"