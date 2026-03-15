### Task 2 – Reservation Import - EXPLANATION

## Running Sync
>- php scripts/sync_reservations.php --from=2026-01-01 --to=2026-01-31

## TODO:
- fetch reservations from API for the date range
- store reservations in DB
- map rooms + rate plans
- support multiple rooms and multiple rate plans per reservation
- generate:
  - lock_id = LOCK-{reservation_id}-{arrival_date}

## Epected flow:
Endpoint: 
POST /reservation/data/reservations



## Known TODO / Next Steps
- napraviti migraciju za pravljenje tabele rezervations
- napraviti migraciju za pravljenje tabele reservation_rooms
- napraviti migraciju za pravljenje tabele reservation_rate_plans
- napraviti migraciju za pravljenje tabele boards

- reservation tabela - cuva po jedan red po rezervaiji, kljuc je external_reservation_id (unique)
U tabeli je obavezno id, externa_property_id, status, date_arrival, date_departure, nights, total_price, extras_price, extras_discounted, city_tax_price, external_pricing_plan_id, external_boards_id, external_invoice_id, date_created, date_modified.
- reservation_rooms tabela - cuva external_room_id, external_boardid, quanitity (1:N veza sa reservation)
Dodatne kolone:id, total_price, nights. 
- reservation_rate_plans tabela - cuva external_pricing_plan_id, external_board_id, reservation_id. (1:N veza sa reservation)

- u helperu u function folderu bi se kreirao slag po pravilu:
LOCK-{reservation_id}-{arrival_date}

“mapiranje sobe” znači: na osnovu external_room_id iz reservation tabele pronaći rooms.external_room_id i upisati rooms.id (lokalni).

“mapiranje sobe” znači: na osnovu external_room_id iz rezervacije pronaći rooms.external_room_id i upisati rooms.id (lokalni).

- Skripta treba da uradi:

session_start()
require setup.php, auth.php, api.php, plus DB + helper funkcije
authenticate($config)
dbConnect($config)
Parse args:
--from=YYYY-MM-DD
--to=YYYY-MM-DD
apiRequest() rezervacije u tom periodu
Upsert u reservations
Za svaku rezervaciju:
upsert “child” rows u reservation_rooms
upsert “child” rows u reservation_rate_plans
Print summary: koliko reservations inserted/updated, koliko room links, koliko rateplan links, koliko skipped

