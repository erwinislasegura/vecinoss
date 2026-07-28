# VecinoSS — CMS de noticias

Aplicación PHP 8.1+ con arquitectura MVC y MySQL para publicar noticias locales y administrarlas desde un panel editorial compacto.

## Instalación

1. Crea la base de datos: `mysql -u root -p < database/schema.sql`.
2. Copia `config/database.example.php` a `config/database.php` y ajusta las credenciales.
3. Inicia el servidor desde la raíz usando `index.php` como router: `php -S localhost:8000 index.php`.
4. Abre `http://localhost:8000` y accede al panel en `/admin`.

Las categorías principales, subcategorías y etiquetas permiten ordenar Noticias, Comunidad, Guía Local y Eventos; VecinoSS TV incluye clasificación por comuna y formato. Para actualizar una instalación previa, ejecuta `mysql -u root -p < database/migrate_content_classification.sql`.

El esquema inicial incluye tres videos de muestra publicados en **VecinoSS TV**. Para agregarlos de forma idempotente a una instalación existente, ejecuta `mysql -u root -p < database/seed_videos.sql`.

El panel incluye un módulo independiente de **Agenda y eventos**. El esquema nuevo incorpora seis eventos de ejemplo; para agregarlos a una instalación existente ejecuta `mysql -u root -p < database/seed_events.sql`.

El usuario inicial es `editor@vecinoss.cl` y la contraseña `Cambiar123!`. **Cámbiala antes de publicar el sitio.**

## Estructura

- `app/Controllers`: controladores del sitio y administración.
- `app/Models`: acceso a datos mediante PDO y consultas preparadas.
- `app/Views`: vistas, layouts y parciales compartidos.
- `public`: CSS centralizado, JavaScript, imágenes y cargas.
- `database/schema.sql`: estructura, índices y datos iniciales.

El `.htaccess` incluido dirige las URL amigables a `index.php` cuando Apache tiene `mod_rewrite` activo. La aplicación también detecta automáticamente si está instalada en un subdirectorio; si el servidor usa una configuración especial, define `APP_BASE_PATH` (por ejemplo, `/vecinoss`). En producción configura HTTPS, desactiva `display_errors` y limita permisos de escritura a `public/uploads`.
