CREATE TABLE IF NOT EXISTS api_news_imports (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 post_id BIGINT UNSIGNED NOT NULL,
 source_url VARCHAR(1000) NOT NULL,
 source_name VARCHAR(180) NOT NULL DEFAULT '',
 commune VARCHAR(40) NOT NULL,
 content_hash CHAR(64) NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 UNIQUE KEY uq_api_source_url (source_url(255)),
 UNIQUE KEY uq_api_content_hash (content_hash),
 INDEX idx_api_commune (commune),
 INDEX idx_api_created_at (created_at),
 CONSTRAINT fk_api_post FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

