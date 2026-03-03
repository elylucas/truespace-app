# Setup Guide

Choose one of the two setup paths below.

## Option A: Local Setup (Herd / Valet / artisan serve)

**Prerequisites:** PHP 8.2+, Composer, Node.js 18+, MySQL 8

```bash
# 1. Clone the repository
git clone <repo-url> interview-app
cd interview-app

# 2. Install dependencies
composer install
npm install && npm run build

# 3. Configure environment
cp .env.example .env
php artisan key:generate

# 4. Create the database
mysql -u root -e "CREATE DATABASE interview_app"

# 5. Run migrations and seed data
php artisan migrate --seed

# 6. Start the application
php artisan serve
# Or add to Laravel Herd / Valet for a .test domain
```

Visit **http://localhost:8000** and log in:
- **Email:** admin@example.com
- **Password:** password

## Option B: Docker

**Prerequisites:** Docker and Docker Compose

```bash
# 1. Clone the repository
git clone <repo-url> interview-app
cd interview-app

# 2. Configure environment
cp .env.example .env

# 3. Build and start containers
docker compose up -d --build
# First run takes a few minutes (installs deps, builds assets, runs migrations)

# 4. Check logs to see when it's ready
docker compose logs -f app
# Wait for "Interview App is running!" message
```

Visit **http://localhost:8000** and log in:
- **Email:** admin@example.com
- **Password:** password

### Docker Commands

```bash
# Stop containers
docker compose down

# Reset database (wipe and re-seed)
docker compose exec app php artisan migrate:fresh --seed

# View logs
docker compose logs -f app

# Shell into the app container
docker compose exec app bash
```

## Seed Data

The seeder creates:

- **3 organizations:** Acme Corp, Globex Industries, Initech Solutions
- **13 users** spread across organizations (admin, managers, members)
- **3 assessments:**
  - "Leadership Effectiveness" (active, 6 questions, ~60% completion)
  - "Team Dynamics Survey" (active, 5 questions, ~40% completion)
  - "Culture Audit" (draft, 8 questions, no responses)

## Troubleshooting

**MySQL connection refused:** Make sure MySQL is running. For Docker, check `docker compose ps` to verify the mysql service is healthy.

**Assets not loading:** Run `npm run build` (or `npm run dev` for hot-reloading during development).

**Permission errors (Docker):** The Docker setup runs as root in the container. If you see permission issues with storage/logs, run: `docker compose exec app chmod -R 777 storage bootstrap/cache`
