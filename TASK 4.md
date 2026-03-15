### Task 4 – Invoice Creation

## Running Sync
>- php scripts/generate_invoice.php --reservation_id=XXXX

## TODO:

- generate invoice payload and insert into invoice_queue
- invoice fields:
    - invoice_number
    - reservation_id
    - guest_name
    - arrival_date
    - departure_date
    - line_items
    - total_amount
    - currency
- numbering format:
    - HS-INV-YYYY-000001
- prevent duplicates in parallel invoice generation (transaction / locking)
- retry sending up to 5 times, then mark as failed

## Epected flow:
Napraviti pouzdan sistem generisanja fakture koji je:
- ne pravi duplikate za istu rezervaciju
- concurrency-safe (dve paralelne skripte ne dobiju isti invoice_number)
- asinhroan preko queue (upis u invoice_queue, a slanje može kasnije)
- ima retry mehanizam do 5 puta

## Known TODO / Next Steps

- session_start()
- require setup.php, auth.php, api.php, DB connect
- authenticate($config) (ako je slanje preko API-ja)

- učita rezervaciju iz lokalne baze:
reservations + reservation_rooms + reservation_room_nights (ili samo header + totals)

- generiše invoice_payload (JSON)

- generiše invoice_number u formatu:
HS-INV-YYYY-000001

- upiše sve u invoice_queue (status pending)
- ispiše rezultat (invoice_number + queue_id)

Šema baze: invoice_queue + numeracija
- invoice_queue tabela
Minimalne kolone:

id (PK)
external_reservation_id (ili local reservation_id)
invoice_number (UNIQUE)
payload_json (LONGTEXT/JSON)
status (pending, processing, sent, failed)
attempts (INT default 0)
last_error (TEXT nullable)
created_at, updated_at
sent_at (nullable)

Idempotency zaštita (preporuka):
- UNIQUE nad external_reservation_id (ako za jednu rezervaciju sme samo jedna faktura), ili
- UNIQUE nad (external_reservation_id, invoice_type) ako planiraš više tipova
To sprečava duplikate kad skriptu pokreneš više puta.

Tabela za numeraciju (da spreči dupliranje broja)

Najčistije rešenje: invoice_sequences (po godini)
year (PK)
last_number (INT)

Kako radi:
za trenutnu godinu uzmem red
u transakciji uvećaš last_number
taj broj koristiš za HS-INV-YYYY-%06d

- Obezbediti da paralelne fakture ne dobiju isti broj

- Invoice payload mora sadržati

invoice_number
reservation_id (external ili local)
guest_name (npr. first_name + last_name)
arrival_date, departure_date
line_items[]
total_amount
currency
Line items 

- Retry mehanizam (do 5 puta)
Najbolje je da to ne radi generate_invoice.php, nego posebna worker skripta.

Worker skripta: process_invoice_queue.php

- Logovanje i audit
INFO - invoice created and queued
ERROR - invoice send failed 
SUCCESS - invoice sent

- DB schema
invoice_queue
invoice_sequences

