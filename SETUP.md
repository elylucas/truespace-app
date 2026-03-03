# Setup Guide

## Docker (any OS, ~5 minutes)

Docker packages everything the app needs (PHP, Node, MySQL) so you don't have to install them yourself. This works the same on macOS, Windows, and Linux.

### 1. Install Docker

If you don't have Docker yet:

- **macOS:** [Docker Desktop for Mac](https://docs.docker.com/desktop/install/mac-install/)
- **Windows:** [Docker Desktop for Windows](https://docs.docker.com/desktop/install/windows-install/) (requires WSL 2 — the installer will guide you)
- **Linux:** [Docker Engine](https://docs.docker.com/engine/install/) + [Docker Compose plugin](https://docs.docker.com/compose/install/linux/)

After installing, make sure Docker is running (you should see the Docker icon in your system tray/menu bar).

### 2. Clone and start

```bash
git clone git@github.com:truespaceco/interview-app.git
cd interview-app
docker compose up --build
```

The first run takes 2-3 minutes. It will:
- Build the container (PHP, Node, Composer)
- Install dependencies
- Start MySQL and wait for it to be healthy
- Run database migrations and seed sample data
- Start the Vite dev server (for CSS/JS hot-reloading)
- Start the app

When you see this, it's ready:

```
=========================================
 Interview App is running!
 Visit: http://localhost:8000
 Login: admin@example.com / password
=========================================
```

### 3. Open the app

Visit **http://localhost:8000** in your browser.

Log in with:
- **Email:** `admin@example.com`
- **Password:** `password`

### Development workflow

You edit files on your machine normally — the Docker container sees your changes through a volume mount.

- **PHP and Blade templates** (controllers, models, views): Edit the file, refresh the browser. Changes show up immediately.
- **CSS and JavaScript** (`resources/css/`, `resources/js/`): The Vite dev server watches for changes and hot-reloads them in the browser automatically.
- **New migrations**: Run them inside the container:
  ```bash
  docker compose exec app php artisan migrate
  ```
- **New packages**:
  ```bash
  docker compose exec app npm install some-package
  docker compose exec app composer require some/package
  ```
- **Any artisan command**: Prefix with `docker compose exec app`:
  ```bash
  docker compose exec app php artisan tinker
  docker compose exec app php artisan route:list
  ```

### Stopping and starting

```bash
# Stop the app (Ctrl+C if running in foreground, or:)
docker compose down

# Start it again (no rebuild needed after the first time)
docker compose up

# Reset the database to its original state
docker compose exec app php artisan migrate:fresh --seed
```

### Running in the background

```bash
docker compose up -d        # Start in background
docker compose logs -f app  # Watch logs (Ctrl+C to stop watching — app keeps running)
docker compose down          # Stop
```

---

## What's in the app

The seeder creates sample data so you have something to work with immediately:

- **3 organizations:** Acme Corp, Globex Industries, Initech Solutions
- **13 users** across the organizations (admin, managers, members)
- **3 assessments** with questions and partial response data

---

## Troubleshooting

**"port 8000 already in use"**
Something else is using port 8000. Either stop it, or change the port in `docker-compose.yml`:
```yaml
ports:
  - "8080:8000"  # Use localhost:8080 instead
```

**"port 3307 already in use"**
Same idea — change the MySQL port mapping in `docker-compose.yml` or stop what's using it. This port is only exposed for convenience (e.g., connecting with a database GUI); the app connects to MySQL internally.

**Permission errors on storage/logs**
```bash
docker compose exec app chmod -R 777 storage bootstrap/cache
```
