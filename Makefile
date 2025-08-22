docker-up:
	@echo "Up All Services"
	docker-compose up -d

docker-composer-install:
	@echo "Execute Composer"
	docker exec -ti fynkus-backend sh -c "composer install"

docker-bootstrap:
	@echo "Create Database if not exists"
	docker exec -ti fynkus-db sh -c "cd /docker-entrypoint-initdb.d/ && mysql -uroot -proot < init.sql"

docker-access-db:
	@echo "Access to DB container"
	docker exec -ti fynkus-db bash

docker-access-backend:
	@echo "Access to Backend container"
	docker exec -ti fynkus-backend bash

docker-down:
	@echo "Down docker-compose"
	rm -rf ./backend/vendor
	rm -rf ./backend/var
	docker-compose down

docker-logs:
	@echo "Watch log in fynkus-backend"
	docker logs -f fynkus-backend

docker-clear-all:
	@echo "⚠️  WARNING: Delete ALL volumes, containers and images"
	docker volume prune -f
	docker system prune -a -f

symfony-lint:
	@echo "Lint PHP"
	docker exec fynkus-backend ./vendor/bin/php-cs-fixer fix --config .php-cs-fixer.dist.php --allow-risky=yes --dry-run

symfony-execute-migrations:
	@echo "Execute Migrations"
	docker exec -ti fynkus-backend sh -c "php bin/console doctrine:migrations:migrate"

symfony-execute-fixtures:
	@echo "Execute Fixtures"
	docker exec -ti fynkus-backend sh -c "php bin/console doctrine:fixtures:load --append"

symfony-create-keys:
	@echo "Create Keys"
	docker exec -ti fynkus-backend sh -c "php bin/console lexik:jwt:generate-keypair"

symfony-test:
	@echo "Execute Tests"
	docker exec -ti fynkus-backend sh -c "APP_ENV=test php bin/phpunit --verbose --configuration phpunit.dist.xml"

symfony-coverage:
	@echo "Execute Coverage Testing"
	docker exec -ti fynkus-backend sh -c "APP_ENV=test XDEBUG_MODE=coverage php bin/phpunit --coverage-text --configuration phpunit.dist.xml"

symfony-router:
	@echo "View Routes"
	docker exec -ti fynkus-backend sh -c "php bin/console debug:router"

symfony-logs:
	@echo "Symfony Logs"
	docker exec -ti fynkus-backend sh -c "tail -f var/log/dev.log"

symfony-doctrine-mapping:
	@echo "Symfony Doctrine Mapping"
	docker exec -ti fynkus-backend sh -c "php bin/console doctrine:mapping:info"
