prepare:
	@echo "Preparing the project..."
	@docker compose up -d --build

setup:
	@echo "Setup the project..."
	@make create-db
	@make schema-update
	@make fixtures-load

create-db:
	@echo "Creating databases..."
	@docker exec -it green_goodies_database psql -U postgres -d postgres -c "DROP DATABASE IF EXISTS green_goodies WITH (FORCE);"
	@docker exec -it green_goodies_database psql -U postgres -d postgres -c "CREATE DATABASE green_goodies;"

schema-update:
	@docker exec -it green_goodies php bin/console doctrine:schema:update --force

make-migration:
	@docker exec -it green_goodies php bin/console make:migration

migration-migrate:
	@docker exec -it green_goodies php bin/console doctrine:migrations:migrate

fixtures-load:
	@docker exec -it green_goodies php bin/console doctrine:fixtures:load

cache-clear:
	@docker exec -it green_goodies php bin/console cache:clear
