# Microservices Lab (Booking System)

Local-only microservices learning environment using Laravel, MySQL, Redis, and Docker Compose.

## Prereqs

- Windows + WSL2 recommended (but this repo also works from Windows PowerShell)
- Docker Desktop (Linux containers)

## Services

- **Gateway**: Nginx reverse proxy on `http://localhost:8080`
- **Availability**: `http://localhost:8080/availability`
- **Bookings**: `http://localhost:8080/bookings`
- **Notifications**: (HTTP for now) `http://localhost:8080/notifications`

## Run (Docker Compose)

From `microservices-lab/`:

### First time only

Each Laravel service already contains a local `.env` (created when scaffolded). If you ever recreate a service folder or clone fresh, do this per service:

```powershell
Copy-Item services/availability/.env.example services/availability/.env
Copy-Item services/bookings/.env.example services/bookings/.env
Copy-Item services/notifications/.env.example services/notifications/.env
```

Then generate app keys (on host):

```powershell
php services/availability/artisan key:generate
php services/bookings/artisan key:generate
php services/notifications/artisan key:generate
```

### Start

```powershell
docker compose up -d --build
```

### Initialize databases

```powershell
docker compose exec availability php artisan migrate
docker compose exec bookings php artisan migrate
docker compose exec notifications php artisan migrate
```

### Seed demo inventory

This creates inventory for **property 1**, room type **STD**, for the next **30** nights, total **5** rooms per night:

```powershell
docker compose exec availability php artisan inventory:seed 1 STD --days=30 --total=5
```

Then verify:

```powershell
curl http://localhost:8080/

curl http://localhost:8080/availability/health
curl http://localhost:8080/bookings/health
curl http://localhost:8080/notifications/health

# Laravel's built-in health endpoint (also works):
curl http://localhost:8080/availability/up

### Set inventory for specific days (Admin)

Inventory is managed per **property + room type + night** in the Availability service.

Set a single night:

```powershell
curl -Method Put http://localhost:8080/availability/api/v1/inventory \
	-ContentType "application/json" \
	-Headers @{ Accept = "application/json" } \
	-Body '{
		"property_id": 1,
		"room_type_code": "STD",
		"date": "2026-03-10",
		"total": 8
	}'
```

Set a date range (inclusive):

```powershell
curl -Method Put http://localhost:8080/availability/api/v1/inventory \
	-ContentType "application/json" \
	-Headers @{ Accept = "application/json" } \
	-Body '{
		"property_id": 1,
		"room_type_code": "STD",
		"from": "2026-03-01",
		"to": "2026-03-07",
		"total": 6
	}'
```

Read inventory for a range:

```powershell
curl "http://localhost:8080/availability/api/v1/inventory?property_id=1&room_type_code=STD&from=2026-03-01&to=2026-03-07"
```
```

### Create a booking (MVP flow)

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

### Search bookings (Admin)

List bookings that overlap a date window (inclusive) so you can see **who booked what**:

```powershell
curl "http://localhost:8080/bookings/api/v1/bookings?property_id=1&from=2026-03-01&to=2026-03-31"
```

Filter by guest email fragment and/or status:

```powershell
curl "http://localhost:8080/bookings/api/v1/bookings?property_id=1&from=2026-03-01&to=2026-03-31&guest_email=guest%40example.com&status=confirmed"
```

### Consume events (Notifications)

Run the consumer (in a separate terminal):

```powershell
docker compose exec notifications php artisan booking-events:consume
```

Or consume once and exit:

```powershell
docker compose exec notifications php artisan booking-events:consume --once
```

Stop:

```powershell
docker compose down -v
```

## Next implementation steps

1. Add `/health` endpoints for each service.
2. Create booking flow: Bookings calls Availability to hold inventory, writes booking, publishes event to Redis.
3. Notifications consumes event and logs/sends a dummy notification.
