COMPOSE_EXEC_PHP=cd infra && docker-compose exec php

install:
	cd infra && docker-compose up -d --build
	${COMPOSE_EXEC_PHP} composer install

test-unit:
	${COMPOSE_EXEC_PHP} bin/phpunit tests/Unit

test-functional:
	${COMPOSE_EXEC_PHP} bin/phpunit tests/Functional

test-all:
	${COMPOSE_EXEC_PHP} bin/phpunit
