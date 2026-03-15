
### Task 1 – Authentication and Catalog Sync - DONE

### Running Sync
Run the main sync script:
>- php scripts/sync_catalog.php

Task 1 sinhronizuje kataloga iz OTAsync/HotelSync API-ja u lokalnu bazu. Ideja je: uloguj se → dobiješ pkey → izvučeš katalog (room types, rooms, pricing plans/rate plans) → upišeš/azuriraš u DB (UPSERT) → loguješ rezultate.

CLI skripta, treba da:
startuje session
uradi login
pozove sync funkcije redom
ispiše rezultat svake sync funkcije (print_r)

### Objasnjenje
- Authenticate (login) na endpoint-u /user/auth/login
Cilj logina je da dobijem pkey (to je runtime key posle logina).

api_token: statički token 
pkey: dinamički key dobijen login-om i tipično važi određeno vreme
Dinamicki pkey čuvam u session-u ( $_SESSION['api_pkey'] )i trebalo bi da se, po dokumentaciji, koristi za naredne requestove. To mi medjutim nije radilo pa sam koristila key iz primera request-a za endpointe koje sam dobila u dokumentaciji. 
To sam i zabeležila u komentarima u kodu.

- Room Types (tabela room_types)
Endpoint: 
POST /room/data/rooms (sa type=1 i details=1)

Funkcija:
syncRoomTypes($conn, $config)

Radi:
uzme response['data'] gde su room types
mapira polja
radi UPSERT u room_types

- Rooms (tabela rooms)
Isti endpoint:
POST /room/data/rooms

Ali sobe su u nested strukturi:
data[] -> roomDetails -> roomNumber[]

Funkcija:
syncRooms($conn, $config)

Radi:
iterira po data (room type rows)
za svaki uzme roomDetails['roomNumber']
mapira id_rooms, name, status, itd.
radi UPSERT u rooms

- Pricing Plans / Rate Plans (tabela pricing_plans)
Endpoint:
POST /pricingPlan/data/pricing_plans

Funkcija:
syncPricingPlans($conn, $config)

Radi:
uzme response['data'] listu planova
generiše slug
radi UPSERT u pricing_plans

- Slug rules (slugs)

rooms: HS-{ROOM_ID}-{slug_room_name}
rate plans: RP-{RATE_PLAN_ID}-{meal_plan}

Radi:
za rooms koristiš createRoomSlug($externalRoomId, $name) - koristi helper function/room_slug.php
za pricing plans si počela da koristiš createRatePlanSlug($externalPricingPlanId, $name) - koristi helper function/rate_plan_slug.php

