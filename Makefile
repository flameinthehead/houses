docker-build:
	docker-compose up --build -d

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down

tinker:
	docker-compose exec php-cli php artisan tinker

migrate:
	docker-compose exec php-cli php artisan migrate

rollback-last:
	docker-compose exec php-cli php artisan migrate:rollback --step=
