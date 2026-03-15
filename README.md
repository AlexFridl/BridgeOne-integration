### BridgeOne Integration (OTAsync API)
CLI PHP integration for syncing hotel data (room types, rooms, availability) from OTAsync API into a local MySQL/MariaDB database.

## Features
API authentication via /user/auth/login
Stores pkey (API key) for subsequent requests in session
Migration runner (creates database + tables)
Logging to per-script daily log files (JSON lines) 
Slug generation helpers:
HS-<roomId>-<slug> for rooms
RP-<ratePlanId>-<slug> for rate plans

### Tech Stack
PHP (CLI)
cURL (API requests)
MySQL/MariaDB (XAMPP)
mysqli for DB access

### Project Structure

# setup.php
Loads config and includes core functions

# config/config.php
Application configuration (API + database)

# functions/
Folder that contains all the helper functions
- api.php
Generic apiRequest() wrapper (cURL)
- auth.php
authenticate() implementation (fetches pkey)
- database.php
dbConnect() helper (mysqli_connect)
- room_slug.php
createRoomSlug($roomId, $roomName)
- rate_plan_slug.php
createRatePlanSlug($ratePlanId, $mealPlan)
- room_types_sync.php
Helper for fetching /room/data/rooms and syncing room types (WIP)

# logger /
Folder that contains logger function and folder
# logs/
Folder that contains all the daily JSON logs per folder named after the script name in which are logs named as script_name_apidate.log
- logger.php 
Logger function that logs to daily log files

Structure:
logs/
- logger.php
- logs/
    - sync_catalog/
        - sync_catalog_api_date*.log
    - etc.
        - etc.

# migrations/
Folder that contains all the SQL migrations (create DB / tables)
000_create_database.sql
001_create_migrations_table.sql
002_create_room_types_table.sql
003_create_rooms_table.sql
004_create_available_rooms_table.sql

# scripts/
Folder that contains all the scripts to be executed
- migrate.php
Migration runner (executes SQL files)
- sync_catalog.php
Main CLI script (authentication + sync tasks)

# webhooks/
Folder that contains webhook handlers


### Configuration
Edit config/config.php:

## API
Base URL:
api_url = https://app.otasync.me/api/

Endpoint:
Receives in script file where is needed

API Credentials:
api_user / api_pass
api_token (static partner token)
Stored in the configuration file

## Database
db.host, db.name, db.user, db.pass
Optional recommended config (if you support multiple properties):

api_property_id (select which id_properties to sync)

## Database Migrations
Migrations are located in:

- database/migrations/
Run migrations from the project root:
>- php scripts/migrate.php

Notes:
- Migrations use CREATE ... IF NOT EXISTS so they can be re-run safely.
- Index sizes are limited on older MariaDB setups, so indexed VARCHAR fields use VARCHAR(190).

## Logging
Logs are written as JSON lines (one JSON object per line).
Each script writes into its own folder:
logs/sync_catalog/

## Authentication (`pkey`) usage
After a successful login (`/user/auth/login`), the API returns a `pkey` value.  
This `pkey` is used as the authorization key for all subsequent API requests that require the `key` parameter.

In this project, the `pkey` is stored in the PHP session (`$_SESSION['api_pkey']`) immediately after authentication and then reused for further requests (e.g. `/room/data/rooms`, `/room/data/available_rooms`, etc.).
> Note: Since this is a CLI integration, the session storage is primarily used within a single script run. For persisting `pkey` across runs, a file/DB cache can be introduced.


## Tasks
Check TASKS.md for more information