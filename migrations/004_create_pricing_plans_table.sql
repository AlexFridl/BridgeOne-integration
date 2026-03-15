-- migrations/004_create_pricing_plans_table.sql

CREATE TABLE IF NOT EXISTS pricing_plans (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,

  external_pricing_plan_id BIGINT UNSIGNED NOT NULL,
  external_property_id BIGINT UNSIGNED NOT NULL,

  name VARCHAR(190) NOT NULL,
  slug VARCHAR(190) NOT NULL,

  external_board_name_id BIGINT UNSIGNED NOT NULL,
  external_policy_id BIGINT UNSIGNED NOT NULL,
  external_restriction_plan_id BIGINT UNSIGNED NOT NULL,
  external_board_id BIGINT UNSIGNED NOT NULL,

  booking_engine TINYINT(1) NOT NULL DEFAULT 0,
  description TEXT NULL,

  type VARCHAR(50) NULL,
  copy_periods INT NULL,
  variation_type VARCHAR(10) NULL,
  variation_amount DECIMAL(12,2) NULL,
  parent_id BIGINT UNSIGNED NULL DEFAULT NULL,
  first_meal VARCHAR(190) NULL,

  date_created DATETIME NULL,

  prices_per_person_active TINYINT(1) NOT NULL DEFAULT 0,
  locked_price TINYINT(1) NOT NULL DEFAULT 0,

  raw LONGTEXT NULL,

  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (id),
  UNIQUE KEY uniq_pricing_plans_external (external_pricing_plan_id),
  KEY idx_pricing_plans_property (external_property_id)
);