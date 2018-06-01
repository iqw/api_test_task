APP_CONTAINER = api_test_task_php

.PHONY: up

pull :
	docker-compose pull

up : build
	docker-compose up -d && sh build-symfony.sh

down :
	docker-compose down

shell :
	docker exec -ti $(APP_CONTAINER) sh

tail :
	docker logs -f $(APP_CONTAINER)

build : pull
	docker-compose build