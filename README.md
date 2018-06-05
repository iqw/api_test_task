# Задание 

Нужно сделать API новостного сайта для управления категориями, статьями

- GET /posts - получение всех статей
- POST /post - создание статьи
- DELETE /post/<id> - удаление статьи
- PUT /post/<id> - обновление статьи
- GET /categories - получение списка категорий

Статья должна принадлежать одной категории. Категория должна иметь счетчик количества принадлежащих ей статей.
Необходимо добавить кэширование данных, применить SOLID принципы.
Не нужно реализовывать авторизацию, разделение на роли.
Также будет плюсом написание UNIT-теста(ов).


# Installation
## Docker + Docker compose
### Linux and OSX `make` installed
- `git clone git@github.com:iqw/api_test_task.git` to clone the repo
- `cd api_test_task && make up` to make all systems up and running
- visit `http://localhost:88/` to check the result

### Windows or Linux or OSX (and all systems without `make`)
- `git clone git@github.com:iqw/api_test_task.git` to clone the repo
- `cd api_test_task` 
- `docker-compose up -d` to make environment up and running
- `cp app/config/parameters.yml.dist app/config/parameters.yml` to copy config sample (edit parameters.yml after that)
- `docker exec -ti api_test_task_php composer install` to install all dependencies with composer
- `docker exec -ti api_test_task_php vendor/bin/phing init` to build symfony project (cache, permissions, migrations)
- visit `http://localhost:88/` to check the result

## Without docker at all
(requirements - php7.1, mysql5.7, redis, composer)
- `git clone git@github.com:iqw/api_test_task.git` to clone the repo
- `cd api_test_task` 
- `cp app/config/parameters.yml.dist app/config/parameters.yml` to copy config sample (edit parameters.yml after that)
- `composer install` to install composer dependencies
- `php vendor/bin/phing init` to build symfony project (cache, permissions, migrations)
- `php bin/console server:run` to run project at `http://localhost:8000/` 

## This environment IS NOT production-ready, just for test task fast run
