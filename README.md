# Backend Starter

A minimal Laravel + SQLite project for a hands-on interview exercise.

## Setup

Make sure you have **PHP >= 8.2** and **Composer** installed.

```bash
git clone <repo-url> backend-starter-interview
cd backend-starter-interview
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Open http://localhost:8000 — you should see the booking UI.

## Verify your setup

```bash
./check-setup.sh
```


## What's provided

- `GET /api/slots` — returns today's time slots with availability (already implemented)
- `TimeSlot` and `Reservation` Eloquent models
- Database already seeded with realistic slot data for today
- A booking UI at `/` that calls your endpoint

## Data model

**`time_slots`**
| column | type | notes |
|---|---|---|
| id | bigint | |
| date | date | |
| time | string | e.g. `"12:00"` |
| period | string | `"lunch"` or `"dinner"` |
| max_covers | tinyint | total capacity |
| booked_covers | tinyint | already reserved |

**`reservations`**
| column | type | notes |
|---|---|---|
| id | bigint | |
| time_slot_id | foreign key | |
| customer_name | string | |
| customer_email | string | |
| covers | tinyint | |
