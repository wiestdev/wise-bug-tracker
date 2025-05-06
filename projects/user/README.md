# User Service

Этот сервис управляет пользователями и авторизацией через JWT

## Содержание

* [Инструкцию по локальному запуску](#инструкцию-по-локальному-запуску)
* [Инструкция по настройке для локального запуска](#инструкция-по-настройке-для-локального-запуска)

## Инструкцию по локальному запуску

```
cd ./docker
docker compose up -d
```

Сервис поднимется на `http://localhost:8000`

## Инструкция по настройке для локального запуска

Скопировать .env с .env.example **(ДАННЫЕ ИЗ DOCKER COMPOSE УЖЕ ВБИТЫ В .env.example)**

## Генерация JWT-секрета

При первом запуске в контейнере должно создаться значение `JWT_SECRET`.  
Если секрет не сгенерировался автоматически, выполните:

```bash
docker compose exec user-service php artisan jwt:secret --force
```

## Миграции и тестовые данные

По умолчанию в `docker-compose.yml` в `command:` для `user-service` уже есть:

```bash
php artisan migrate --no-interaction
```

Если хотите сбросить БД:

```bash
docker compose exec user-service php artisan migrate:fresh --seed
```

---

## Тестирование

```bash
#Запустить тесты:
docker compose exec user-service php artisan test --testsuite=Unit
```

## API-эндпоинты

Все маршруты доступны по префиксу `/api`. Заголовок для защищённых маршрутов:

```
Authorization: Bearer <ваш_JWT_токен>
```

| Метод | URL                | Описание                      | Авторизация | Тело запроса (JSON)                                                       |
|-------|--------------------|-------------------------------|-------------|---------------------------------------------------------------------------|
| **POST**  | `/api/register`    | Регистрация нового пользователя | нет         | `{ "name": "Иван", "email": "ivan@example.com", "password": "secret" }`    |
| **POST**  | `/api/login`       | Авторизация, возвращает JWT     | нет         | `{ "email": "ivan@example.com", "password": "secret" }`                   |
| **GET**   | `/api/me`          | Профиль текущего пользователя    | да          | —                                                                         |
| **POST**  | `/api/logout`      | Инвалидация текущего токена      | да          | —                                                                         |
| **POST**  | `/api/refresh`     | Получить новый токен по refresh  | да          | —                                                                         |

### Примеры запросов

#### Регистрация

```bash
curl -X POST http://localhost:8000/api/register   -H "Content-Type: application/json"   -d '{"name":"Иван","email":"ivan@example.com","password":"secret"}'
```

#### Логин

```bash
curl -X POST http://localhost:8000/api/login   -H "Content-Type: application/json"   -d '{"email":"ivan@example.com","password":"secret"}'
```

Ответ при успешном логине:

```json
{
  "user": {
    "id": 1,
    "name": "Иван",
    "email": "ivan@example.com",
    "created_at": "...",
    "updated_at": "..."
  },
  "token": "<JWT_TOKEN>"
}
```

#### Профиль

```bash
curl http://localhost:8000/api/me   -H "Authorization: Bearer <JWT_TOKEN>"
```

---