# User Stories (MVP)

## Customer

1. As a customer, I want to search availability for a property and date range so that I can plan my stay.
2. As a customer, I want to search availability across multiple properties so that I can choose the best location.
3. As a customer, I want to view room types and prices for a selected property and dates so that I can compare options.
4. As a customer, I want to create a booking so that I can reserve a room type for my dates.
5. As a customer, I want to view my booking details so that I can confirm my reservation.
6. As a customer, I want to cancel my booking so that I can change my plans.

## Staff/Admin (Backoffice)

7. As staff, I want to create/update room types so that availability can be sold.
8. As staff, I want to set inventory per room type per night so that we can control sellable rooms.
9. As staff, I want to close sales for a date (stop-sell) so that rooms are not bookable.
10. As staff, I want to search bookings by date/property so that I can support guests.

## Must-demo stories

### A) Search availability
**Given** a property and stay dates
**When** I request availability
**Then** I receive available room types with remaining inventory per night (simplified for MVP)

### B) Create booking (hold → confirm)
**Given** availability exists for requested room type and dates
**When** I create a booking
**Then** inventory is held/confirmed and a booking ID is returned

### C) Cancel booking (release inventory)
**Given** I have a confirmed booking
**When** I cancel it
**Then** inventory is released and I receive cancellation confirmation
