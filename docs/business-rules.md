# Business Rules (MVP)

## Booking lifecycle

- **Draft**: not persisted (UI-only / client state)
- **Held**: inventory is held temporarily
- **Confirmed**: booking is confirmed and inventory is deducted
- **Cancelled**: booking cancelled, inventory released (subject to policy)
- **Expired**: hold expired and inventory released

## Inventory rules

- Inventory is managed per **Property + RoomType + Night**.
- The **Availability service** owns inventory and hold/confirm logic.
- The **Bookings service** must not write to Availability’s database.

## Holds

- Hold TTL: **15 minutes** (configurable later).
- A booking cannot move to **Confirmed** unless inventory was successfully held.

## Consistency

- Expect **eventual consistency** across services.
- Events are delivered **at-least-once** → consumers must be idempotent.

## Cancellation

- Cancelling a confirmed booking releases inventory.
- Cancellation penalty rules are out of MVP; treat as free cancellation for now.
