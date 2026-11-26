# Tripora REST API Documentation

Base URL (standard local folder name):

```text
http://localhost/Tripora/api/index.php
```

All responses use JSON:

```json
{
  "success": true,
  "message": "Packages retrieved.",
  "data": []
}
```

## Authentication and session

The API uses PHP sessions. Login creates a session cookie. Browser requests use the same-origin cookie automatically.

For requests that change data (`POST`, `PUT`, `PATCH`, `DELETE`), send:

```text
X-CSRF-Token: <token>
```

Fetch a token from `GET /auth/csrf`. After successful login, the response contains a rotated `data.csrf_token`. The Postman collection included with this project demonstrates this flow.

| Method | Endpoint | Auth | Purpose |
|---|---|---|---|
| GET | `/auth/csrf` | Public | Issue/read session CSRF token |
| POST | `/auth/register` | Public + CSRF | Register a normal user |
| POST | `/auth/login` | Public + CSRF | Sign in and create session |
| GET | `/auth/profile` | Login | Get current account profile |
| POST | `/auth/logout` | Login + CSRF | Destroy current session |
| GET | `/dashboard/stats` | Admin | Get dashboard counters |

### Register request

```json
{
  "full_name": "Tripora Member",
  "email": "member@example.test",
  "phone": "081234567890",
  "password": "MemberTripora123"
}
```

The API always assigns the `user` role. It ignores any client-supplied role value.

### Login request

```json
{
  "email": "admin@tripora.local",
  "password": "TriporaAdmin!2026"
}
```

## Packages

| Method | Endpoint | Access | Purpose |
|---|---|---|---|
| GET | `/packages` | Public | List active packages; admin sees all |
| GET | `/packages/{id}` | Public | Get one active package; admin can view inactive |
| POST | `/packages` | Admin + CSRF | Create package |
| PUT | `/packages/{id}` | Admin + CSRF | Update package |
| DELETE | `/packages/{id}` | Admin + CSRF | Delete package |

Example create/update payload:

```json
{
  "category_id": 1,
  "destination_id": 2,
  "package_name": "Bromo Sunrise Adventure",
  "description": "Discover the sunrise at Mount Bromo.",
  "price": 1200000,
  "duration": "2 Days 1 Night",
  "facilities": "Jeep; Local guide; Accommodation",
  "itinerary": "Day 1: Arrival\nDay 2: Sunrise tour and departure",
  "image_url": "https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?w=900",
  "status": "active"
}
```

## Categories

| Method | Endpoint | Access | Purpose |
|---|---|---|---|
| GET | `/categories` | Public | List categories and package count |
| GET | `/categories/{id}` | Public | Get one category |
| POST | `/categories` | Admin + CSRF | Create category |
| PUT | `/categories/{id}` | Admin + CSRF | Update category |
| DELETE | `/categories/{id}` | Admin + CSRF | Delete category |

Example payload:

```json
{
  "category_name": "Nature & Adventure",
  "description": "Explore mountains and outdoor adventures.",
  "image_url": "assets/images/categories/nature.jpg"
}
```

## Destinations

| Method | Endpoint | Access | Purpose |
|---|---|---|---|
| GET | `/destinations` | Public | List destinations and package count |
| GET | `/destinations/{id}` | Public | Get one destination |
| POST | `/destinations` | Admin + CSRF | Create destination |
| PUT | `/destinations/{id}` | Admin + CSRF | Update destination |
| DELETE | `/destinations/{id}` | Admin + CSRF | Delete destination |

Example payload:

```json
{
  "destination_name": "Labuan Bajo",
  "country": "Indonesia",
  "city": "Labuan Bajo",
  "description": "An island destination in East Nusa Tenggara.",
  "image_url": "assets/images/destinations/labuan-bajo.jpg"
}
```

## Status codes

| Code | Meaning |
|---:|---|
| 200 | Request successful |
| 201 | Record created |
| 401 | Login required or credentials invalid |
| 403 | Authenticated account does not have admin permission |
| 404 | Endpoint or record not found |
| 409 | Duplicate value or record is still referenced by another record |
| 419 | CSRF token missing or expired |
| 422 | Input validation failed |
| 500 | Server/database error; check PHP error log and MySQL setup |

## Database relationships

- `categories` 1-to-many `packages`.
- `destinations` 1-to-many `packages`.
- `users` stores both admin and user accounts, differentiated by `role`.

The API uses PDO prepared statements for data queries and InnoDB foreign keys to protect related records. A category or destination cannot be deleted while packages still reference it.
