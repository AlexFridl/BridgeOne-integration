### Task 3  – Reservation Update / Cancel

## Running Sync
>- php scripts/update_reservation.php --reservation_id=XXXX

## TODO:

- fetch reservation
- check if it exists locally
- compare payload hash
- update if changed
- write changes into audit_log table
- if canceled:
  - keep reservation row
  - add audit event

## Epected flow:




## Known TODO / Next Steps
