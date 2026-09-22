https://gymflow-api-h7e9.onrender.com

# GymFlow API

Backend API for **GymFlow**, a gym management system built to replace manual, WhatsApp/paper-based class booking and membership tracking with a proper multi-gym platform.

**Live demo:** [add your Railway URL here]
**Frontend repo:** [gymflow-web](https://github.com/Moayad-Mac/gymflow-web)

## What it does

GymFlow lets a gym chain manage:

- Multiple gym locations
- Recurring weekly classes with trainers and capacity limits
- Member bookings for specific class sessions
- Membership plans and subscriptions (chain-wide access)
- Staff-managed check-ins at the door

## Tech stack

- **Laravel 13** (PHP)
- **MySQL**
- **Laravel Sanctum** for token-based API authentication
- Deployed on **Railway**

## Roles

The API supports three roles, each with a separate profile table linked to `users`:

| Role        | Can do                                                                                  |
| ----------- | --------------------------------------------------------------------------------------- |
| **Member**  | Browse classes, book/cancel their own bookings, view their own subscription             |
| **Trainer** | View their own classes and the roster (confirmed bookings) for each                     |
| **Staff**   | Full access — manage classes, manage subscriptions, check members in, view all bookings |

## Key architecture decisions

- **Classes are immutable.** A class can be created or deleted, but never edited. Editing a live class (time, trainer, capacity) creates silent conflicts with existing member bookings — staff cancel and recreate instead, which keeps booking data trustworthy.
- **Subscription status is checked live, not just stored.** Rather than relying on a scheduled job to flip `active` → `expired`, every check compares the stored status against the actual expiry date at request time. Simpler to reason about for a project this size, with the same correctness guarantee.
- **Bookings reference a specific `class_date`, not just a class.** Since classes are recurring weekly templates, a booking needs to record _which_ occurrence a member reserved — this is what makes capacity checks and duplicate-booking checks possible.
- **Booking and check-in are separate concerns.** A booking is a reservation made in advance; a check-in is proof of attendance on the day. This split lets the system distinguish a booked-but-no-show from an actual visit.

## Setup

```bash
git clone https://github.com/Moayad-Mac/gymflow-api.git
cd gymflow-api
composer install
cp .env.example .env
php artisan key:generate
```

Set your database credentials in `.env`, then:

```bash
php artisan migrate --seed
php artisan serve
```

Seeded accounts (password for all: `password`):

- Staff: `nadine.staff@gymflow.test`
- Trainer: `karim.trainer@gymflow.test`
- Member: `tarek@gymflow.test`

## Core endpoints

| Method | Endpoint                   | Description                                                   |
| ------ | -------------------------- | ------------------------------------------------------------- |
| POST   | `/register`                | Member self-registration                                      |
| POST   | `/login`                   | Login, returns Sanctum token                                  |
| GET    | `/gym-classes`             | List classes (scoped by role)                                 |
| POST   | `/gym-classes`             | Create a class (staff only)                                   |
| POST   | `/bookings`                | Book a class session                                          |
| DELETE | `/bookings/{id}`           | Cancel a booking                                              |
| GET    | `/gym-classes/{id}/roster` | View a class's confirmed bookings (trainer, own classes only) |
| POST   | `/subscriptions`           | Create a subscription (staff only)                            |
| POST   | `/check-in`                | Check in a booking (staff only)                               |
