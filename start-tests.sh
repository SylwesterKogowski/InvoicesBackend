#!/bin/sh

if [[ -z "$DOCKER_HOST" ]]; then
	docker compose exec workspace bash start-tests.sh
else
	echo "Preparing the test database"
	php artisan migrate --database=test
	echo "Starting tests"
	php artisan test
fi
