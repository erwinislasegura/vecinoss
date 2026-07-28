USE vecinoss;

-- Datos opcionales para instalaciones existentes. Cada ejemplo se agrega una sola vez.
INSERT INTO videos (title,description,cover_image,video_url,status,published_at)
SELECT 'Video de muestra: YouTube','Contenido demostrativo para revisar el reproductor de VecinoSS TV con un enlace estándar de YouTube.','https://img.youtube.com/vi/M7lc1UVf-VE/hqdefault.jpg','https://www.youtube.com/watch?v=M7lc1UVf-VE','published',DATE_SUB(NOW(),INTERVAL 1 HOUR)
WHERE NOT EXISTS (SELECT 1 FROM videos WHERE video_url='https://www.youtube.com/watch?v=M7lc1UVf-VE');

INSERT INTO videos (title,description,cover_image,video_url,status,published_at)
SELECT 'Video de muestra: archivo multimedia','Ejemplo audiovisual que permite comprobar la portada, el título y la reproducción desde la sección principal.','https://img.youtube.com/vi/aqz-KE-bpKQ/hqdefault.jpg','https://www.youtube.com/watch?v=aqz-KE-bpKQ','published',DATE_SUB(NOW(),INTERVAL 2 HOUR)
WHERE NOT EXISTS (SELECT 1 FROM videos WHERE video_url='https://www.youtube.com/watch?v=aqz-KE-bpKQ');

INSERT INTO videos (title,description,cover_image,video_url,status,published_at)
SELECT 'Video de muestra: enlace corto de YouTube','Ejemplo de una URL corta compatible con el reproductor integrado y administrable desde el panel.','https://img.youtube.com/vi/ScMzIvxBSi4/hqdefault.jpg','https://youtu.be/ScMzIvxBSi4','published',DATE_SUB(NOW(),INTERVAL 3 HOUR)
WHERE NOT EXISTS (SELECT 1 FROM videos WHERE video_url='https://youtu.be/ScMzIvxBSi4');
