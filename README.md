# Backend Starter

A minimal Laravel + SQLite project for a hands-on interview exercise.

## Setup

Make sure you have **Docker** installed and running.

```bash
./install.sh
./sail up -d
./sail artisan migrate:fresh --seed
```

Go to http://localhost:8888.

## Running tests

```bash
./sail test
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
