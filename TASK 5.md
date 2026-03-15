### Task 5 – Webhook Endpoint

## Create endpoint:
POST /webhooks/otasync.php

## Testing
Send POST request to /webhooks/otasync.php using Postman/curl

## TODO:
Napraviti endpoint:
- POST /webhooks/otasync.php
Endpoint treba da:
- primi webhook event
- validira payload
- izračuna payload hash
- sačuva event u bazu
- proveri da li je event već obrađen
- ažurira rezervaciju u bazi
Webhook treba da podrži:
- nova rezervacija
- izmena rezervacije
- otkazivanje rezervacije
Ako isti webhook stigne više puta, sistem ne sme napraviti duple zapise.


## Epected flow:
Implementirati endpoint POST /webhooks/otasync.php koji prima webhook događaje iz OTAsync-a i sinhronizuje lokalnu bazu tako da:

može da obradi:
- new reservation
- updated reservation
- canceled reservation

- isti webhook više puta ne pravi duple zapise
- obezbedi audit/logging i stabilan rad čak i kad webhook pravi više requestova

Pre obrade proveravam da payload sadrži obavezna polja koja dokumentacija navodi:

data_type (npr. "reservation", "avail", "prices")
action (npr. "insert", "edit", "cancel")
data (objekat sa stvarnim podacima)
Ako nešto fali logujem i vraćam 400.

## DB 
- tabela za webhook events
- tabela za reservation updates

## HTTP odgovori:

200 OK za uspeh i za duplikate (idempotency)
400 za nevalidan payload
405 za pogrešnu metodu
401 ako signature/authorization ne prođe (ako se implementira)
