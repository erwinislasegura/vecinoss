CREATE TABLE IF NOT EXISTS advertisements (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(140) NOT NULL,
  image VARCHAR(500) NOT NULL,
  target_url VARCHAR(1000) NOT NULL,
  alt_text VARCHAR(180) NOT NULL,
  open_new_tab TINYINT(1) NOT NULL DEFAULT 1,
  sort_order INT NOT NULL DEFAULT 0,
  status ENUM('draft','published') NOT NULL DEFAULT 'draft',
  starts_at DATETIME NOT NULL,
  ends_at DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_advertisements_public (status,starts_at,ends_at,sort_order)
) ENGINE=InnoDB;
