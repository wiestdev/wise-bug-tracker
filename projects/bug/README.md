# Bug Service

Микросервис управления проектами и баг-репортами. Позволяет пользователям создавать проекты и добавлять к ним баги. Использует авторизацию через user-service

## Содержание

* [Инструкцию по локальному запуску](#инструкцию-по-локальному-запуску)
* [Инструкция по настройке для локального запуска](#инструкция-по-настройке-для-локального-запуска)

## Инструкцию по локальному запуску

```
cd ./docker
docker compose up -d
```

Сервис поднимется на `http://localhost:8001`

## Инструкция по настройке для локального запуска

Скопировать .env с .env.example **(ДАННЫЕ ИЗ DOCKER COMPOSE УЖЕ ВБИТЫ В .env.example)**

## 📚 API-эндпоинты

| Метод | URL                                | Описание                          | Авторизация |
|-------|-------------------------------------|-----------------------------------|-------------|
| GET   | `/api/projects`                    | Получить список проектов текущего пользователя | ✅ |
| POST  | `/api/projects`                    | Создать новый проект              | ✅ |
| GET   | `/api/projects/{id}`               | Получить проект по ID             | ✅ |
| GET   | `/api/projects/{id}/bugs`          | Список багов в проекте            | ✅ |
| POST  | `/api/projects/{id}/bugs`          | Добавить баг в проект             | ✅ |

---

## 🧪 Примеры запросов

### Создать проект

```bash
curl -X POST http://localhost:8001/api/projects   -H "Authorization: Bearer <JWT_TOKEN>"   -H "Content-Type: application/json"   -d '{"title":"BugTracker","description":"Мой первый проект"}'
```

### Получить проекты

```bash
curl -H "Authorization: Bearer <JWT_TOKEN>"      http://localhost:8001/api/projects
```

### Добавить баг

```bash
curl -X POST http://localhost:8001/api/projects/1/bugs   -H "Authorization: Bearer <JWT_TOKEN>"   -H "Content-Type: application/json"   -d '{"title":"Кнопка не работает","description":"Ошибка на главной"}'
```

---
