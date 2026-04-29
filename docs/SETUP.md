# Setup & Quickstart — Microservices Lab (Booking System)

This document shows how to get the project running locally and how to exercise the HTTP UI/endpoints in a browser.

Prerequisites
- Docker Desktop (Linux containers)
- Git
- (Optional) PHP and Composer only needed if you prefer installing dependencies outside containers

Quick start (Windows / PowerShell)

1. Copy environment files for each service (one-time or when cloning fresh):

```powershell
Copy-Item services/availability/.env.example services/availability/.env
Copy-Item services/bookings/.env.example services/bookings/.env
Copy-Item services/notifications/.env.example services/notifications/.env
```

2. Generate Laravel app keys (host):

```powershell
php services/availability/artisan key:generate
php services/bookings/artisan key:generate
php services/notifications/artisan key:generate
```

3. Build & start all services (Docker Compose):

```powershell
docker compose up -d --build
```

4. Initialize the databases (migrations):

```powershell
docker compose exec availability php artisan migrate --force
docker compose exec bookings php artisan migrate --force
docker compose exec notifications php artisan migrate --force
```

5. Seed demo inventory (creates inventory for property `1`, room type `STD`):

```powershell
docker compose exec availability php artisan inventory:seed 1 STD --days=30 --total=5
```

Where to open the app in a browser
- Gateway root: http://localhost:8080/  — shows quick links and routes to services
- Availability: http://localhost:8080/availability
- Bookings: http://localhost:8080/bookings
- Notifications: http://localhost:8080/notifications

Quick API examples (PowerShell / curl)
- Create a booking (POST):

```powershell
curl -Method Post http://localhost:8080/bookings/api/v1/bookings \
  -ContentType "application/json" \
  -Headers @{ Accept = "application/json" } \
  -Body '{
    "property_id": 1,
    "room_type_code": "STD",
    "check_in": "2026-03-01",
    "check_out": "2026-03-03",
    "guest_email": "guest@example.com"
  }'
```

- Read inventory for a date range:

```powershell
curl "http://localhost:8080/availability/api/v1/inventory?property_id=1&room_type_code=STD&from=2026-03-01&to=2026-03-07"
```

Troubleshooting notes
- If you see an error about `vendor/autoload.php` missing when hitting endpoints, this means the running container is using a bind-mounted service directory that lacks `vendor/` (common when you start containers before running `composer install`). Solutions:
  - Run `composer install` inside the container: `docker compose exec -T availability composer install --no-interaction --prefer-dist` (repeat for `bookings` and `notifications`), or
  - Rebuild images after ensuring Composer can finish in the build (this repo sets `COMPOSER_PROCESS_TIMEOUT=0` in the service Dockerfiles to avoid timeouts during image build).

- To view logs for a service: `docker compose logs -f availability`

- To stop everything and remove volumes: `docker compose down -v`

Developer notes
- Service source is under `services/availability`, `services/bookings`, `services/notifications`.
- The gateway is in `gateway/nginx.conf` and proxies requests on `localhost:8080` to the services.
- If you want to run the Laravel app locally outside Docker, install PHP 8.4+ and Composer, run `composer install` in each service and then `php artisan serve`.

Useful commands summary

```powershell
# build and run
docker compose up -d --build

# migrate
docker compose exec availability php artisan migrate --force

# seed demo inventory
docker compose exec availability php artisan inventory:seed 1 STD --days=30 --total=5

# health check (browser or curl)
# http://localhost:8080/
# http://localhost:8080/availability/health
```

If you want, I can add a small UI smoke-test script (Playwright or simple curl-based) to exercise the main flows.
