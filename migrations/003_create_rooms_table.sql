CREATE TABLE IF NOT EXISTS rooms (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,

  external_room_type_id BIGINT UNSIGNED NOT NULL,  
  external_room_id BIGINT UNSIGNED NOT NULL,
  external_property_id BIGINT UNSIGNED NOT NULL,  

  name VARCHAR(190) NOT NULL,
  slug VARCHAR(190) NOT NULL,
  status VARCHAR(50) NOT NULL,
  is_available BOOLEAN DEFAULT FALSE,

  is_deleted BOOLEAN DEFAULT FALSE,

  external_created_at DATETIME NULL,
  external_deleted_at DATETIME NULL,
  

  raw_json LONGTEXT NULL,
  
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  UNIQUE KEY uq_external_room (external_room_id),
  UNIQUE KEY uq_rooms_slug (slug),
  INDEX idx_room_type (external_room_type_id),

  CONSTRAINT fk_rooms_room_types
    FOREIGN KEY (external_room_type_id)
    REFERENCES room_types (external_room_type_id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT);
