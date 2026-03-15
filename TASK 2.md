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

Request: 
curl --location 'https://app.otasync.me/api/reservation/data/reservations' \
--data '{
    "token": "a5666bee05b0fa91afc5c2f56a6cdcfd57a58c89",
    "key": "574eb98879eb28d03b21e8a5c1a21259a9a5c85f",
    "id_properties": 93,
    
    "channels": [],
    "countries": [],
    "order_by": "date_received",
    "rooms": [],
    "arrivals": 0,
    "companies": [],
    "contigents": [],
    "departures": 0,
    "dfrom": "2026-01-01",
    "dto": "2026-01-31",
    "filter_by": "date_received",
    "max_nights": "",
    "max_price": "",
    "min_nights": "",
    "min_price": "",
    "multiple_properties": "0",
    "offer_expiring": "0",
    "order_type": "desc",
    "page": 1,
    "pricing_plans": [],
    "search": "",
    "show_nights": 1,
    "show_rooms": 1,
    "status": "0",
    "view_type": "reservations"
}'

Respons:
{
  "total_pages_number": 1,
  "page": "1",
  "totals": {
    "total": 1,
    "check_in": 0,
    "check_out": 0,
    "total_amount": 7798.5
  },
  "reservations": [
    {
      "id_reservations": "2567127",
      "id_properties": "93",
      "status": "confirmed",
      "guest_status": "waiting_arrival",
      "reservation_type": "incentive",
      "guest_check_in": null,
      "guest_check_out": null,
      "pending_until": "2023-11-27",
      "pending_time": "",
      "date_received": "2026-01-13",
      "time_received": "20:59:21",
      "date_arrival": "2023-11-27",
      "date_departure": "2023-11-30",
      "date_canceled": null,
      "custom_price": null,
      "nights": "3",
      "total_price": "7798.5",
      "remaining_amount": "7779.5",
      "rooms_price": "855",
      "rooms_discounted": "855",
      "extras_price": "2358",
      "extras_discounted": "2358",
      "city_tax_price": "4500",
      "insurance_price": "0",
      "board_price": "0",
      "board_discounted": "0",
      "conference_halls_price": "0",
      "spas_price": "0",
      "room_discount": "0",
      "extras_discount": "0",
      "board_discount": "0",
      "discount_type": "percent",
      "discount_amount": "0",
      "custom_tax_rate": "10",
      "custom_tax_name": "test",
      "custom_tax_price": "85.5",
      "note": "Note 1",
      "private_note": "Private",
      "attachment": null,
      "id_pricing_plans": "370",
      "id_boards": "835",
      "id_city_taxes": "1",
      "id_invoices": null,
      "id_promocodes": "0",
      "id_channels": "392",
      "id_primary_guests": "4896407",
      "children_1": "0",
      "children_2": "0",
      "children_3": "0",
      "children_4": "0",
      "children_5": "0",
      "children_6": "0",
      "children_7": "0",
      "adults": "150",
      "seniors": "0",
      "has_card": "0",
      "total_guests": "150",
      "parking_count": "0",
      "parking_note": "",
      "reference": "reference mobile",
      "exchange_rate": "1",
      "additional_exchange_rate": "1",
      "date_modified": "2026-01-13 20:59:21",
      "date_created": "2026-01-13 20:59:21",
      "channel_name": "Private reservation",
      "channel_logo": "https://app.otasync.me/img/ota/youbook.png",
      "first_name": "Viktor",
      "last_name": "Test",
      "pricing_plan_name": "NETO CIJENA",
      "rooms_include": "no",
      "rooms_tax": "10",
      "guests": [],
      "extras": [],
      "payments": [],
      "invoices": [],
      "rooms": [
        {
          "id_rooms": "323",
          "room_number": "AA",
          "id_reservations_rooms": "3340152",
          "adults": "50",
          "seniors": "0",
          "total_guests": "50",
          "avg_price": "95",
          "total_price": "285",
          "first_meal": "breakfast",
          "discount_type": "percent",
          "discount_amount": "0",
          "date_arrival": "2023-11-27",
          "date_departure": "2023-11-30",
          "nights_count": "3",
          "discounted_price": "285",
          "id_room_types": "170",
          "name": "2-Bedroom Apartment with Sea View",
          "shortname": "2BDs",
          "status": "confirmed",
          "nights": [
            {
              "id_reservations_nights": "17345896",
              "night_date": "2023-11-27",
              "breakfast": "50",
              "lunch": "50",
              "dinner": "50",
              "price": "95",
              "price_discounted": "95",
              "original_price": "95"
            },
            {
              "id_reservations_nights": "17345897",
              "night_date": "2023-11-28",
              "breakfast": "50",
              "lunch": "50",
              "dinner": "50",
              "price": "95",
              "price_discounted": "95",
              "original_price": "95"
            },
            {
              "id_reservations_nights": "17345898",
              "night_date": "2023-11-29",
              "breakfast": "50",
              "lunch": "50",
              "dinner": "50",
              "price": "95",
              "price_discounted": "95",
              "original_price": "95"
            }
          ]
        },
        {
          "id_rooms": "324",
          "room_number": "2A3",
          "id_reservations_rooms": "3340153",
          "adults": "50",
          "seniors": "0",
          "total_guests": "50",
          "avg_price": "95",
          "total_price": "285",
          "first_meal": "breakfast",
          "discount_type": "percent",
          "discount_amount": "0",
          "date_arrival": "2023-11-27",
          "date_departure": "2023-11-30",
          "nights_count": "3",
          "discounted_price": "285",
          "id_room_types": "170",
          "name": "2-Bedroom Apartment with Sea View",
          "shortname": "2BDs",
          "status": "confirmed",
          "nights": [
            {
              "id_reservations_nights": "17345899",
              "night_date": "2023-11-27",
              "breakfast": "50",
              "lunch": "50",
              "dinner": "50",
              "price": "95",
              "price_discounted": "95",
              "original_price": "95"
            },
            {
              "id_reservations_nights": "17345900",
              "night_date": "2023-11-28",
              "breakfast": "50",
              "lunch": "50",
              "dinner": "50",
              "price": "95",
              "price_discounted": "95",
              "original_price": "95"
            },
            {
              "id_reservations_nights": "17345901",
              "night_date": "2023-11-29",
              "breakfast": "50",
              "lunch": "50",
              "dinner": "50",
              "price": "95",
              "price_discounted": "95",
              "original_price": "95"
            }
          ]
        },
        {
          "id_rooms": "2971",
          "room_number": "2",
          "id_reservations_rooms": "3340154",
          "adults": "50",
          "seniors": "0",
          "total_guests": "50",
          "avg_price": "95",
          "total_price": "285",
          "first_meal": "breakfast",
          "discount_type": "percent",
          "discount_amount": "0",
          "date_arrival": "2023-11-27",
          "date_departure": "2023-11-30",
          "nights_count": "3",
          "discounted_price": "285",
          "id_room_types": "170",
          "name": "2-Bedroom Apartment with Sea View",
          "shortname": "2BDs",
          "status": "confirmed",
          "nights": [
            {
              "id_reservations_nights": "17345902",
              "night_date": "2023-11-27",
              "breakfast": "50",
              "lunch": "50",
              "dinner": "50",
              "price": "95",
              "price_discounted": "95",
              "original_price": "95"
            },
            {
              "id_reservations_nights": "17345903",
              "night_date": "2023-11-28",
              "breakfast": "50",
              "lunch": "50",
              "dinner": "50",
              "price": "95",
              "price_discounted": "95",
              "original_price": "95"
            },
            {
              "id_reservations_nights": "17345904",
              "night_date": "2023-11-29",
              "breakfast": "50",
              "lunch": "50",
              "dinner": "50",
              "price": "95",
              "price_discounted": "95",
              "original_price": "95"
            }
          ]
        }
      ]
    }
  ],
  "currency": "EUR"
}

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

