# Приложение Books

###  Docker + Yii2 + MySql

---

## 🚀 Состав

- **php-fpm** — контейнер с PHP 8.4 и установленными расширениями (`intl`, `pdo_mysql`, `zip`, `opcache`)
- **nginx** — веб-сервер, проксирующий запросы к PHP
- **mysql** — база данных MySQL 8.4
- **composer** — отдельный контейнер для установки зависимостей PHP
- *(опционально можно добавить phpMyAdmin и Mailhog)*

---

## ⚙️ Конфигурация окружения

Файл **.env** содержит параметры БД:

```env
MYSQL_ROOT_PASSWORD=root
MYSQL_DATABASE=yii2_app
MYSQL_USER=yii2_user
MYSQL_PASSWORD=secret
```

---

## Установка и запуск приложения

### Вариант 1: ручными командами

```bash
docker compose build
docker compose up -d
docker compose run --rm composer install
docker compose exec php php yii migrate --interactive=0
docker compose exec php php yii import/books
```

### Вариант 2: через Makefile
```bash
make init
```

**Откройте приложение в браузере:**
   👉 [http://localhost:8080](http://localhost:8080)
