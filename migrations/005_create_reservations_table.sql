CREATE TABLE IF NOT EXISTS reservations (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,

  external_reservation_id BIGINT UNSIGNED NOT NULL,
  external_property_id BIGINT UNSIGNED NOT NULL,

  lock_id VARCHAR(64) NOT NULL,
  payload_hash CHAR(64) NULL,

  status VARCHAR(32) NOT NULL,
  guest_status VARCHAR(32) NULL,
  reservation_type VARCHAR(32) NULL,

  date_received DATE NULL,
  time_received TIME NULL,

  channel_name VARCHAR(190) NULL,
  first_name VARCHAR(190) NULL,
  last_name VARCHAR(190) NULL,
  reference VARCHAR(190) NULL,

  arrival_date DATE NOT NULL,
  departure_date DATE NOT NULL,
  date_canceled DATE NULL,

  nights INT NOT NULL,

  total_price DECIMAL(12,2) NULL,
  remaining_amount DECIMAL(12,2) NULL,

  rooms_price DECIMAL(12,2) NULL,
  rooms_discounted DECIMAL(12,2) NULL,
  extras_price DECIMAL(12,2) NULL,
  extras_discounted DECIMAL(12,2) NULL,
  city_tax_price DECIMAL(12,2) NULL,
  custom_tax_price DECIMAL(12,2) NULL,

  external_pricing_plan_id BIGINT UNSIGNED NULL,
  external_board_id BIGINT UNSIGNED NULL,
  external_channel_id BIGINT UNSIGNED NULL,
  external_primary_guest_id BIGINT UNSIGNED NULL,

  external_created_at DATETIME NULL,
  external_modified_at DATETIME NULL,

  raw_json LONGTEXT NULL,

  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (id),

  UNIQUE KEY uniq_reservations_external (external_reservation_id),
  UNIQUE KEY uniq_reservations_lock (lock_id),

  KEY idx_reservations_property_dates (external_property_id, arrival_date, departure_date),
  KEY idx_reservations_status (status),
  KEY idx_reservations_pricing_plan (external_pricing_plan_id),
  KEY idx_reservations_channel (external_channel_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;