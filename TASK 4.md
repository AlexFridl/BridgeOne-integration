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


## Known TODO / Next Steps