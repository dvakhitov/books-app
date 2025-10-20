.PHONY: build up migrate import down

# Сборка контейнеров
build:
	docker compose build

# Запуск контейнеров в фоне
up:
	docker compose up -d

# Применение миграций Yii2
migrate:
	sleep 10
	docker compose exec php php yii migrate --interactive=0

# Импорт книг
import:
	docker compose exec php php yii import/books

# Полный старт: сборка, запуск, миграции и импорт
init: build up install migrate import

# Остановка и удаление контейнеров
down:
	docker compose down -v

install:
	docker compose run --rm composer install