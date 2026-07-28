CREATE DATABASE IF NOT EXISTS vecinoss CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE vecinoss;

CREATE TABLE users (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(180) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE categories (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(80) NOT NULL,
  slug VARCHAR(100) NOT NULL UNIQUE,
  description VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE posts (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_id BIGINT UNSIGNED NOT NULL,
  user_id BIGINT UNSIGNED NOT NULL,
  title VARCHAR(180) NOT NULL,
  slug VARCHAR(200) NOT NULL UNIQUE,
  excerpt VARCHAR(350),
  body LONGTEXT NOT NULL,
  image VARCHAR(500),
  status ENUM('draft','published') NOT NULL DEFAULT 'draft',
  featured TINYINT(1) NOT NULL DEFAULT 0,
  published_at DATETIME NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_posts_public (status, published_at),
  INDEX idx_posts_category (category_id),
  CONSTRAINT fk_posts_category FOREIGN KEY (category_id) REFERENCES categories(id),
  CONSTRAINT fk_posts_user FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB;

INSERT INTO users (name,email,password) VALUES
('Equipo VecinoSS','editor@vecinoss.cl','$2y$12$zLgzc4.Y2YWVesK5mWEXm.tEgWNvFp6dza.J8to/eMozWi112YH5.');

INSERT INTO categories (name,slug,description) VALUES
('Noticias','noticias','La actualidad de la Provincia de San Antonio.'),
('Comunidad','comunidad','Historias, organizaciones y voces de nuestros barrios.'),
('Seguridad','seguridad','Información útil y prevención para la comunidad.'),
('Deportes','deportes','El deporte local y sus protagonistas.'),
('Cultura','cultura','Arte, patrimonio y panoramas de la provincia.'),
('Emprendimiento','emprendimiento','Comercio, empresas y talento local.');

INSERT INTO posts (category_id,user_id,title,slug,excerpt,body,image,status,featured,published_at) VALUES
(2,1,'Vecinos impulsan una nueva jornada de recuperación de espacios públicos','vecinos-impulsan-recuperacion-espacios-publicos','Organizaciones locales convocaron a familias y voluntarios para renovar una de las plazas más concurridas de la comuna.','La comunidad volvió a demostrar que el trabajo colaborativo puede transformar los barrios. Desde temprano, vecinas y vecinos se reunieron para limpiar, pintar y recuperar las áreas verdes de la plaza.\n\nLa actividad contó con la participación de organizaciones sociales, emprendedores y familias del sector. Sus organizadores adelantaron que la iniciativa se repetirá en otros puntos de la provincia.','https://images.unsplash.com/photo-1559027615-cd4628902d4a?auto=format&fit=crop&w=1400&q=80','published',1,NOW()),
(1,1,'Municipio anuncia mejoras de conectividad para distintos sectores','municipio-anuncia-mejoras-conectividad','El plan considera nuevas obras y coordinación con las comunidades durante las próximas semanas.','Las autoridades dieron a conocer un calendario de trabajos que busca mejorar los tiempos de traslado y la seguridad vial. Los detalles serán comunicados a cada sector antes del inicio de las obras.','https://images.unsplash.com/photo-1518005020951-eccb494ad742?auto=format&fit=crop&w=900&q=80','published',0,DATE_SUB(NOW(),INTERVAL 1 HOUR)),
(4,1,'Clubes locales preparan sus próximos encuentros deportivos','clubes-locales-preparan-encuentros','Las series juveniles y adultas afinan los últimos detalles para una fecha llena de actividad.','Los recintos de la provincia recibirán una nueva jornada deportiva. Los clubes invitaron a la comunidad a acompañar a sus equipos y disfrutar en familia.','https://images.unsplash.com/photo-1461896836934-ffe607ba8211?auto=format&fit=crop&w=900&q=80','published',0,DATE_SUB(NOW(),INTERVAL 2 HOUR)),
(5,1,'Agenda cultural reúne música, patrimonio y actividades familiares','agenda-cultural-musica-patrimonio','Conoce los panoramas gratuitos que se realizarán este fin de semana en la provincia.','Diversos espacios culturales abrirán sus puertas con una programación para todas las edades. La agenda incluye música en vivo, recorridos patrimoniales y talleres.','https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=900&q=80','published',0,DATE_SUB(NOW(),INTERVAL 3 HOUR)),
(6,1,'Feria local abre nuevas oportunidades para emprendedores','feria-local-oportunidades-emprendedores','Productores y comercios de la zona mostrarán sus propuestas en una nueva feria abierta a la comunidad.','La actividad busca fortalecer la economía local y conectar directamente a emprendedores con sus clientes. Habrá gastronomía, artesanía y servicios.','https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?auto=format&fit=crop&w=900&q=80','published',0,DATE_SUB(NOW(),INTERVAL 4 HOUR)),
(3,1,'Refuerzan recomendaciones para prevenir emergencias en el hogar','recomendaciones-prevenir-emergencias-hogar','Equipos especializados compartieron consejos prácticos y canales de atención.','La prevención comienza con acciones sencillas. Las autoridades recomendaron revisar instalaciones, mantener despejadas las vías de evacuación y tener visibles los números de emergencia.','https://images.unsplash.com/photo-1452421822248-d4c2b47f0c81?auto=format&fit=crop&w=900&q=80','published',0,DATE_SUB(NOW(),INTERVAL 5 HOUR));

