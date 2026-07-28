# VecinoSS — CMS de noticias

Aplicación PHP 8.1+ con arquitectura MVC y MySQL para publicar noticias locales y administrarlas desde un panel editorial compacto.

## Instalación

1. Crea la base de datos: `mysql -u root -p < database/schema.sql`.
2. Copia `config/database.example.php` a `config/database.php` y ajusta las credenciales.
3. Inicia el servidor desde la raíz usando `index.php` como router: `php -S localhost:8000 index.php`.
4. Abre `http://localhost:8000` y accede al panel en `/admin`.

El usuario inicial es `editor@vecinoss.cl` y la contraseña `Cambiar123!`. **Cámbiala antes de publicar el sitio.**

## Estructura

- `app/Controllers`: controladores del sitio y administración.
- `app/Models`: acceso a datos mediante PDO y consultas preparadas.
- `app/Views`: vistas, layouts y parciales compartidos.
- `public`: CSS centralizado, JavaScript, imágenes y cargas.
- `database/schema.sql`: estructura, índices y datos iniciales.

El `.htaccess` incluido dirige las URL amigables a `index.php` cuando Apache tiene `mod_rewrite` activo. La aplicación también detecta automáticamente si está instalada en un subdirectorio; si el servidor usa una configuración especial, define `APP_BASE_PATH` (por ejemplo, `/vecinoss`). En producción configura HTTPS, desactiva `display_errors` y limita permisos de escritura a `public/uploads`.
