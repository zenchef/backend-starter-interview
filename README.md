# Backend Starter

A minimal Laravel + SQLite project for a hands-on interview exercise.

## Setup

Make sure you have **Docker** installed and running.

```bash
 cp .env.example .env

 docker run --rm --interactive --tty \
  --volume $PWD:/app \
  --user $(id -u):$(id -g) \
  composer install

ln -s ./vendor/bin/sail .
chmod +x sail

./sail up -d
./sail artisan migrate:fresh --seed
./sail npm install
./sail npm run build
```

Go to http://localhost:8888.

---

## Interview options

### A. Display dates and slots

Open the booking page — dates and available times are empty.
Fill up holes in the code so that it's possible to choose a datetime option. 

### B. Review `StoreBookingController`

The booking form now works. Let's do a code review of  `app/Http/Controllers/StoreBookingController.php`.
No code to write here — just a discussion.

### C. Implement `GetOccupationsForDate` (TDD)

Implement `app/Actions/GetOccupationsForDate.php`. Tests are already written in `tests/Feature/Actions/GetOccupationsForDateTest.php` — make them pass.

```bash
./sail artisan test --filter=GetOccupationsForDateTest
```

This action powers two features:

- **Display occupation** for each time on the booking page.
- **Prevent overbooking** in the booking creation flow.

Useful constants live in `config/restaurant.php`:

- `booking_duration` — how many consecutive slots a single booking occupies.
- `slot_capacity` — maximum guests allowed on a single slot.
