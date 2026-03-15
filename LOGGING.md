### Logging
Logging sistem zamišljen je kao centralizovan sistem koji u svakoj skripti u kojoj je pokrenut beleži sve akcije koje se dešavaju u sistemu. Beleži šta je pokušano da se uradi, da li je uspelo ili ne i neki kontekst za debug.
U kodu se to radi pomoću helper funkcije logMessage(...).

## TODO:
ensure logs include:
- timestamp
- event type
- description
- reservation ID or external ID when available

## Running Sync
Folder logs/ sadrži folder /logger i /logs. 
Folder /logger sadrži fajl logger.php koji sadrži funkciju logMessage().
Folder /logs sadrži fajlove sa logovima po danima, koji su grupisani po folderima koji se zovu po skriptama u kojima su pokrenuti. 

logMessage() funkcija je jedan ulaz za sve logove. 
Funkcija sadrži standardizovan zapis:
- timestamp - kada se desila akcija
- level type (INFO / ERROR / SUCCESS)
- description - šta se desilo
- context payload - asocijativni niz sa detaljima za debug

Ovo je bitno jer omogućava:
- konzistentan forma loga u celoj aplikaciji
- brzo filtriranje problema po level ili po external_* ID-ijevima
- rešavanje bagova bez vardump / print_r po scriptama

Trenutno se loguje:
- Auth / Login (authenticate)
- API layer
- DB layer
- Sync layer

## Log Format
```
[2025-10-15 14:30:45] [INFO] Reservation created successfully {"reservation_id": "12345", "external_id": "EXT-67890"}
[2025-10-15 14:30:46] [ERROR] Failed to update reservation {"reservation_id": "12345", "error": "Connection timeout"}
[2025-10-15 14:30:47] [SUCCESS] Reservation confirmed {"reservation_id": "12345", "confirmation_code": "ABC123"}
```

