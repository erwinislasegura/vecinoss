-- Ejecutar una sola vez sobre una instalación existente de VecinoSS.
CREATE TABLE IF NOT EXISTS settings (
  setting_key VARCHAR(100) PRIMARY KEY,
  setting_value TEXT NOT NULL,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO settings (setting_key, setting_value) VALUES
('horoscope_enabled', '1'),
('horoscope_cta_eyebrow', 'TU GUÍA DEL DÍA'),
('horoscope_cta_title', 'Horóscopo diario'),
('horoscope_cta_text', 'Descubre qué tienen preparado los astros para tu signo.'),
('horoscope_cta_button', 'Ver mi horóscopo'),
('horoscope_page_title', 'Horóscopo de hoy'),
('horoscope_page_intro', 'Consulta las predicciones para los doce signos del zodiaco.')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);
