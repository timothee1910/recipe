#!/bin/sh

# PHP executable for VSCode

CONTAINER_ID=$(docker ps | grep "app" | awk '{ print $1 }' |head -n 1)
docker exec $CONTAINER_ID php "$@"

exit $?
