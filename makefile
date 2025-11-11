prepare:
	@echo "Preparing the project..."
	@docker compose up -d --build

setup:
	@echo "Setup the project..."
	@make create-db
	@make install-deps
	@make schema-update
	@make fixtures-load
	@make generate-jwt-keys

create-db:
	@echo "Creating databases..."
	@docker exec -it green_goodies_database psql -U postgres -d postgres -c "DROP DATABASE IF EXISTS green_goodies WITH (FORCE);"
	@docker exec -it green_goodies_database psql -U postgres -d postgres -c "CREATE DATABASE green_goodies;"

schema-update:
	@docker exec -it green_goodies php bin/console doctrine:schema:update --force

install-deps:
	@echo "Installing dependencies..."
	@docker exec -it green_goodies composer install -o

make-migration:
	@docker exec -it green_goodies php bin/console make:migration

migration-migrate:
	@docker exec -it green_goodies php bin/console doctrine:migrations:migrate

fixtures-load:
	@docker exec -it green_goodies php bin/console doctrine:fixtures:load --no-interaction

generate-jwt-keys:
	@docker exec -it green_goodies php bin/console lexik:jwt:generate-keypair --skip-if-exists
	# --overwrite if you want to overwrite existing keys.

cache-clear:
	@docker exec -it green_goodies php bin/console cache:clear
	@php bin/console cache:clear
