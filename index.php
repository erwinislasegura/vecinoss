<?php
declare(strict_types=1);

require __DIR__ . '/app/bootstrap.php';

use App\Controllers\AdminController;
use App\Controllers\SiteController;

$path = '/' . trim((string) parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$path = $path === '//' ? '/' : $path;
$method = $_SERVER['REQUEST_METHOD'];
$site = new SiteController();
$admin = new AdminController();

if ($path === '/' && $method === 'GET') $site->home();
elseif ($path === '/admin/login' && $method === 'GET') $admin->login();
elseif ($path === '/admin/login' && $method === 'POST') $admin->authenticate();
elseif ($path === '/admin/logout' && $method === 'POST') $admin->logout();
elseif ($path === '/admin' && $method === 'GET') $admin->dashboard();
elseif ($path === '/admin/posts' && $method === 'GET') $admin->posts();
elseif ($path === '/admin/posts/create' && $method === 'GET') $admin->create();
elseif ($path === '/admin/posts' && $method === 'POST') $admin->store();
elseif (preg_match('#^/admin/posts/(\d+)/edit$#', $path, $m) && $method === 'GET') $admin->edit((int) $m[1]);
elseif (preg_match('#^/admin/posts/(\d+)$#', $path, $m) && $method === 'POST' && ($_POST['_method'] ?? '') === 'DELETE') $admin->destroy((int) $m[1]);
elseif (preg_match('#^/admin/posts/(\d+)$#', $path, $m) && $method === 'POST') $admin->store((int) $m[1]);
elseif (preg_match('#^/noticia/([a-z0-9-]+)$#', $path, $m) && $method === 'GET') $site->article($m[1]);
elseif (preg_match('#^/categoria/([a-z0-9-]+)$#', $path, $m) && $method === 'GET') $site->category($m[1]);
else { http_response_code(404); $site->category('__not_found__'); }

