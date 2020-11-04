init: docker-down-clear api-clear docker-pull docker-build docker-up api-init
up: docker-up
down: docker-down
restart: down up
check: lint analyze
lint: api-lint
analyze: api-analyze

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build

api-clear:
	docker run --rm -v ${PWD}/api:/app -w /app alpine sh -c 'rm -rf var/cache/*'

api-init: api-permissions api-composer-install api-wait-for-db api-migrate

api-permissions:
	docker run --rm -v ${PWD}/api:/app -w /app alpine mkdir -p -- var/cache
	docker run --rm -v ${PWD}/api:/app -w /app alpine chmod 777 var/cache

api-composer-install:
	docker-compose run --rm api-php-cli composer install

api-wait-for-db:
	docker-compose run --rm api-php-cli wait-for-it api-postgres:5432 -t 30

api-migrate:
	docker-compose run --rm api-php-cli composer app migrations:migrate

api-lint:
	docker-compose run --rm api-php-cli composer lint
	docker-compose run --rm api-php-cli composer cs-check

api-analyze:
	docker-compose run --rm api-php-cli composer psalm

run:
	docker-compose run --rm api-php-cli $(command)
