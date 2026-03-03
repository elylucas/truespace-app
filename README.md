# Interview App

> **Confidential** — This repository is shared for interview purposes only. Please do not redistribute.

A Laravel application for managing organizational assessments. Built with Laravel 10, Livewire 3, Alpine.js, and Tailwind CSS.

## Getting Started

1. **[Set up the app](SETUP.md)** — Docker (recommended) or local PHP. Takes about 5 minutes.
2. **[Read the interview tasks](INTERVIEW_TASKS.md)** — Two features to implement.
3. Create a branch (`candidate/<your-name>`), do the work, open a PR to `main`.

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
