COMMIT = $(shell git rev-parse --short HEAD)
TIMESTAMP = $(shell date +%Y%m%d%H%M%S)

M = $(shell printf "\033[32;1m▶\033[0m")

include .env

.SILENT:

.DEFAULT_GOAL := help

.PHONY: version
version:
	echo $(TIMESTAMP)-$(COMMIT)

.PHONY: help
help: ; $(info $(M) Usage:)
	echo "make run                      build environment"
	echo "make tests                    run tests"

.PHONY: up
up: ; $(info $(M) Starting containers:)
	docker-compose up -d

.PHONY: down
down: ; $(info $(M) Shutting down containers:)
	docker-compose down

.PHONY: composer
composer: ; $(info $(M) Installing backend dependencies:)
	docker-compose exec -T php composer validate --ansi --no-interaction --strict
	docker-compose exec -T php composer install --ansi --no-interaction

.PHONY: yarn
yarn: ; $(info $(M) Installing frontend dependencies:)
	docker-compose run -T --rm encore yarn install --frozen-lockfile --non-interactive

.PHONY: migrations
migrations: ; $(info $(M) Running migrations:)
	echo -n "Waiting for SQL container to be up and running"
	until docker-compose exec -T postgres pg_isready -h postgres --quiet ; \
        do \
            echo -n "." ; \
            sleep 1 ; \
        done
	echo ""
	docker-compose exec -T php bin/console migrations:migrate --ansi --allow-no-migration --no-interaction
	docker-compose exec -T php bin/console --env test migrations:migrate --ansi --allow-no-migration --no-interaction

.PHONY: run
run: yarn up composer migrations ; $(info $(M) Environment has been built succesfully)

.PHONY: static-analyze
static-analyze: ; $(info $(M) Performing static analyze:)
	docker-compose exec -T php vendor/bin/phpstan analyse --ansi
	docker-compose exec -T php vendor/bin/psalm --taint-analysis
	docker-compose exec -T php vendor/bin/deptrac --ansi --fail-on-uncovered --no-interaction --cache-file=var/cache/deptrac.cache

.PHONY: styles-check
styles-check: ; $(info $(M) Checking coding style:)
	docker-compose exec -T php vendor/bin/phpcs -ps
	docker-compose exec -T encore yarn lint

.PHONY: tests
tests: run styles-check static-analyze ; $(info $(M) Running tests:)
	docker-compose exec -T php vendor/bin/phpunit
	docker-compose exec -T encore yarn tests
