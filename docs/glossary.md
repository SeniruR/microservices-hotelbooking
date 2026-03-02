# Glossary (Hotel Booking)

- **Property (Hotel)**: A single hotel location under the same brand.
- **Room Type**: Category of room (e.g., Standard King, Deluxe Twin).
- **Room**: A physical room instance (optional for MVP; can model only room types).
- **Night**: A date in the stay range; inventory is managed per night.
- **Stay**: Date range from check-in (inclusive) to check-out (exclusive).
- **Inventory**: Sellable capacity for a Room Type on a Night at a Property.
- **Availability**: Computed view of inventory after holds/bookings/closures.
- **Hold (Reservation Hold)**: Temporary lock on inventory for a guest before confirmation.
- **Booking**: Confirmed reservation for a stay (guest + property + room type + dates).
- **Guest**: The customer making the booking.
- **Cancellation**: Guest-initiated booking termination with defined policy.
- **No-show**: Guest does not arrive; policy defines penalty (optional).
