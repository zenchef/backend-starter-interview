# Backend Starter

A minimal Laravel + SQLite project for a hands-on interview exercise.

## Setup

Make sure you have **Docker** installed and running.

```bash
./install.sh
./sail up -d
./sail artisan migrate:fresh --seed
./sail npm install
./sail npm run dev
```

Go to http://localhost:8888.

---

## Interview steps

### 1. Display dates and slots

Open the booking page — dates and slots are empty.

Start from `app/Http/Controllers/ShowBookingPageController.php` and implement `app/Actions/GetBookableSlotsByDate.php` so the page lists every bookable slot grouped by date.

The action's docblock describes the expected shape and ordering.

### 2. Review `StoreBookingController`

The booking form now works. Take a look at `app/Http/Controllers/StoreBookingController.php` and discuss:

- What do you think of the **current validation**? What would you improve?
- If we needed to add a **logging mechanism** around booking creation, how would you approach it?

No code to write here — just a discussion.

### 3. Implement `GetOccupationsForDate` (TDD)

Implement `app/Actions/GetOccupationsForDate.php`. Tests are already written in `tests/Feature/Actions/GetOccupationsForDateTest.php` — make them pass.

```bash
./sail artisan test --filter=GetOccupationsForDateTest
```

This action powers two features:

- **Display occupation** for each slot on the booking page.
- **Prevent overbooking** in the booking creation flow.

Useful constants live in `config/restaurant.php`:

- `booking_duration` — how many consecutive slots a single booking occupies.
- `slot_capacity` — maximum guests allowed on a single slot.
