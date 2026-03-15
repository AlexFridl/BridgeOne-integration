CREATE TABLE IF NOT EXISTS room_types (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    external_room_type_id BIGINT UNSIGNED NOT NULL,
    external_property_id BIGINT UNSIGNED NOT NULL,

    name VARCHAR(255),

    type VARCHAR(255),
    shortname VARCHAR(255), 

    price DECIMAL(10, 2),
    occupancy INT,
    bathrooms INT,

    description TEXT NULL,

    is_deleted BOOLEAN DEFAULT FALSE,
    
    external_created_at DATETIME NULL,
    external_updated_at DATETIME NULL,

    raw_json LONGTEXT NULL,

    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY unique_room_type (external_room_type_id)
);