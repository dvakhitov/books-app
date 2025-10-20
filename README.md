# Yii2 Docker Environment (PHP 8.4 + Nginx + MySQL)

Полноценная среда разработки Yii2 на Docker с PHP 8.4 (FPM), Nginx и MySQL 8.4.  
Подходит для работы с шаблоном `yiisoft/yii2-app-basic` или `advanced`.

---

## 🚀 Состав

- **php-fpm** — контейнер с PHP 8.4 и установленными расширениями (`intl`, `pdo_mysql`, `zip`, `opcache`)
- **nginx** — веб-сервер, проксирующий запросы к PHP
- **mysql** — база данных MySQL 8.4
- **composer** — отдельный контейнер для установки зависимостей PHP
- *(опционально можно добавить phpMyAdmin и Mailhog)*

---

## 📁 Структура проекта

```
my-yii2-app/
│
├── docker-compose.yml
├── Dockerfile
├── .env
├── nginx/
│   └── default.conf
└── src/
    ├── web/
    │   └── index.php
    ├── config/
    ├── composer.json
    └── ...
```

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

## 🧩 Установка и запуск

1. **Клонируйте репозиторий:**
   ```bash
   git clone https://github.com/dvakhitov/yii2-books.git
   cd my-yii2-app
   ```

2. **Создайте базовую структуру и скачайте Yii2:**
   ```bash
   docker-compose run --rm composer create-project yiisoft/yii2-app-basic .
   ```
   *(команда создаст проект внутри папки `src/`)*

3. **Запустите контейнеры:**
   ```bash
   docker-compose up -d --build
   ```

4. **Откройте приложение в браузере:**
   👉 [http://localhost:8080](http://localhost:8080)

---

## 🗄 Подключение к базе данных

В `src/config/db.php` пропишите:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=' . getenv('MYSQL_HOST') . ';dbname=' . getenv('MYSQL_DATABASE'),
    'username' => getenv('MYSQL_USER'),
    'password' => getenv('MYSQL_PASSWORD'),
    'charset' => 'utf8',
];
```

Данные для подключения:
```
Host: db
Port: 3306
Database: yii2_app
User: yii2_user
Password: secret
```

---

## 🧰 Полезные команды

**Список контейнеров:**
```bash
docker ps
```

**Установка зависимостей Yii2:**
```bash
docker-compose run --rm composer install
```

**Вход в контейнер PHP:**
```bash
docker exec -it php-fpm bash
```

**Просмотр логов Nginx:**
```bash
docker logs nginx
```

**Перезапуск контейнеров:**
```bash
docker-compose restart
```

---

## 🧹 Очистка и пересборка

```bash
docker-compose down -v
docker-compose up -d --build
```

---

## 🧩 Расширение окружения

Можно легко добавить:

- **phpMyAdmin** для доступа к базе:
  ```yaml
  phpmyadmin:
    image: phpmyadmin/phpmyadmin
    restart: always
    ports:
      - "8081:80"
    environment:
      - PMA_HOST=db
      - MYSQL_ROOT_PASSWORD=${MYSQL_ROOT_PASSWORD}
    depends_on:
      - db
  ```

  → открой [http://localhost:8081](http://localhost:8081)

- **Mailhog** для перехвата писем (SMTP для dev):
  ```yaml
  mailhog:
    image: mailhog/mailhog
    ports:
      - "8025:8025"
  ```
  → открой [http://localhost:8025](http://localhost:8025)

---

## 🧠 Полезно знать

- Папка `src/` монтируется как volume, поэтому изменения в коде сразу видны в контейнере.
- Все данные MySQL сохраняются в volume `db_data`, не теряются при перезапуске.
- Порты:
    - Nginx: **8080**
    - MySQL: **3306**
    - phpMyAdmin (если включён): **8081**
    - Mailhog (если включён): **8025**

---

## ✅ Готово!

Теперь у тебя полностью рабочая среда Yii2 в Docker.  
Можно разрабатывать, деплоить и масштабировать без лишних настроек.