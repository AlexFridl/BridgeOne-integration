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
CLI skripta treba da uzme jednu rezervaciju iz API-ja, uporedi je sa onim što već imam u bazi i onda:
- ako je ista - ne radi ništa (no changes log)
- ako se razlikuje - update lokalih tabela + audit event 
- ako je otkazana - rezervacija ostaje u bazi samo status postaje canceled + audit event

## API deo 
Endpoint:
POST /reservation/data/reservation
šalje se filter:
search: "<reservation_id>"

Obavezno: show_rooms=1, show_nights=1, da se dobije kompletan payload koji možeš upoređivati i upisivati.

## DB deo (šta moraš da imaš u tabelama)
Da bi hash upoređivanje bilo jednostavno, u reservations tabeli treba da postoji:

external_reservation_id (unique)
status
raw_json (celi reservation payload)
payload_hash (SHA256 ili MD5)
updated_at (timestamp)

Kada ovo ne postoji Task 2 nije završen, i u Task 3 ćeš to dodati kroz migraciju.

## Payload hashing (kako se radi pravilno)
Hash brzo detektuješ promene bez ručnog poređenja 50 polja.

Uzmet rezervaciju kao niz (array)
Napravi “canonical JSON” (stabilan):
json_encode($reservation, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)

Hash:
$hash = hash('sha256', $json);

## Audit log tabela (šta da upisuješ)
Napraviti tabelu audit_log (ili reservation_audit_log) sa kolonama:

id (PK)
entity_type (npr. reservation)
external_entity_id
event_type (npr. created, updated, canceled, no_change)
old_hash (nullable)
new_hash
old_payload (LONGTEXT)
new_payload (LONGTEXT)
created_at


## Known TODO / Next Steps
session_start()
require setup.php, auth.php, api.php, DB connect
authenticate($config)

Parse CLI arg --reservation_id
Fetch reservation iz API-ja
Proveri lokalno:
SELECT payload_hash, status, raw_json FROM reservations WHERE external_reservation_id=?
Izračunati new_hash

Cases:
Case 1: Ne postoji lokalno

Case 2: Postoji lokalno, hash isti
upiši audit event no_change (opciono)

Case 3: Postoji lokalno, hash različit
update reservation header + child tabele (rooms + nights) 
UPSERT
upiši audit event updated

Case 4: Otkazana rezervacija
status == 'canceled' ili date_canceled != null
update reservations.status na canceled + upiši date_canceled
ne brišeš ništa
audit event: canceled





