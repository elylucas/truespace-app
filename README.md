# Interview App

> **Confidential** — This repository is shared for interview purposes only. Please do not redistribute.

A Laravel application for managing organizational assessments. Built with Laravel 10, Livewire 3, Alpine.js, and Tailwind CSS.

## Quick Start

See [SETUP.md](SETUP.md) for detailed setup instructions (local or Docker).

**TL;DR:**
```bash
cp .env.example .env
composer install && npm install && npm run build
php artisan key:generate
# Create MySQL database "interview_app", then:
php artisan migrate --seed
php artisan serve
```

Login: **admin@example.com** / **password**

## Interview Tasks

See [INTERVIEW_TASKS.md](INTERVIEW_TASKS.md) for the feature tasks to implement.

## Tech Stack

- **Backend:** Laravel 10 (PHP 8.2)
- **Frontend:** Livewire 3, Alpine.js, Tailwind CSS
- **Database:** MySQL 8
- **Build:** Vite
- **Auth:** Laravel Breeze (email/password)

## Project Structure

```
app/
├── Http/Controllers/     # AssessmentController, DashboardController, UserController
├── Livewire/             # TakeAssessment component
└── Models/               # Organization, User, Assessment, Question, Response

resources/views/
├── assessments/          # index, show, edit, take
├── layouts/              # app layout, navigation
├── livewire/             # take-assessment component view
└── users/                # index

database/
├── migrations/           # Schema definitions
└── seeders/              # Deterministic seed data
```

## Submitting Your Work

1. Create a branch: `candidate/<your-name>`
2. Implement the tasks in [INTERVIEW_TASKS.md](INTERVIEW_TASKS.md)
3. Push your branch and open a PR to `main`
