### Task 5 – Webhook Endpoint

## Create endpoint:
POST /webhooks/otasync.php

## TODO:

- receive webhook event
- validate payload
- compute payload hash
- store event in DB
- ensure idempotency (same webhook multiple times must not duplicate)
- update local reservation state
- support events:
    - new reservation
    - reservation update
    - reservation cancel

## Epected flow:


## Known TODO / Next Steps