-- Configuración de los iconos de redes sociales del encabezado.
-- La sentencia es idempotente y puede ejecutarse más de una vez.
CREATE TABLE IF NOT EXISTS settings (
  setting_key VARCHAR(100) PRIMARY KEY,
  setting_value TEXT NOT NULL,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO settings (setting_key,setting_value) VALUES
('social_facebook_enabled','0'),('social_facebook_url',''),
('social_instagram_enabled','0'),('social_instagram_url',''),
('social_x_enabled','0'),('social_x_url',''),
('social_youtube_enabled','0'),('social_youtube_url',''),
('social_whatsapp_enabled','0'),('social_whatsapp_url','')
ON DUPLICATE KEY UPDATE setting_key=VALUES(setting_key);
