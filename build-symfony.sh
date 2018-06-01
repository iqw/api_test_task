#!/bin/sh

cp app/config/parameters.yml.dist app/config/parameters.yml
docker exec -ti api_test_task_php composer install
docker exec -ti api_test_task_php vendor/bin/phing init