<?php

session_start();

require __DIR__ . '/../app/config/config.php';
require __DIR__ . '/../app/core/Database.php';
require __DIR__ . '/../app/core/helpers.php';
require __DIR__ . '/../app/models/User.php';
require __DIR__ . '/../app/models/PropertyType.php';
require __DIR__ . '/../app/models/Property.php';
require __DIR__ . '/../app/models/PropertyRequest.php';
require __DIR__ . '/../app/controllers/AuthController.php';
require __DIR__ . '/../app/controllers/HomeController.php';
require __DIR__ . '/../app/controllers/PropertyController.php';
require __DIR__ . '/../app/controllers/RequestController.php';
require __DIR__ . '/../app/controllers/AdminController.php';

$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$base = trim(parse_url(BASE_URL, PHP_URL_PATH), '/');
if ($base !== '' && str_starts_with($path, $base)) {
    $path = trim(substr($path, strlen($base)), '/');
}
$method = $_SERVER['REQUEST_METHOD'];

try {
    match (true) {
        $path === '' => (new HomeController())->index(),
        $path === 'properties' => (new PropertyController())->catalog(),
        preg_match('/^properties\/(\d+)$/', $path, $m) === 1 => (new PropertyController())->show((int)$m[1]),
        $path === 'login' && $method === 'GET' => (new AuthController())->loginForm(),
        $path === 'login' && $method === 'POST' => (new AuthController())->login(),
        $path === 'register' && $method === 'GET' => (new AuthController())->registerForm(),
        $path === 'register' && $method === 'POST' => (new AuthController())->register(),
        $path === 'logout' => (new AuthController())->logout(),
        $path === 'request' && $method === 'GET' => (new RequestController())->form(),
        $path === 'request' && $method === 'POST' => (new RequestController())->store(),
        $path === 'dashboard' => (new RequestController())->dashboard(),
        $path === 'admin' => (new AdminController())->dashboard(),
        $path === 'admin/properties' && $method === 'GET' => (new AdminController())->properties(),
        $path === 'admin/properties/save' && $method === 'POST' => (new AdminController())->saveProperty(),
        $path === 'admin/properties/delete' && $method === 'POST' => (new AdminController())->deleteProperty(),
        $path === 'admin/types' && $method === 'GET' => (new AdminController())->types(),
        $path === 'admin/types/save' && $method === 'POST' => (new AdminController())->saveType(),
        $path === 'admin/types/delete' && $method === 'POST' => (new AdminController())->deleteType(),
        $path === 'admin/requests' => (new AdminController())->requests(),
        $path === 'admin/requests/status' && $method === 'POST' => (new AdminController())->requestStatus(),
        $path === 'admin/users' && $method === 'GET' => (new AdminController())->users(),
        $path === 'admin/users/admin' && $method === 'POST' => (new AdminController())->createAdmin(),
        $path === 'admin/users/role' && $method === 'POST' => (new AdminController())->updateRole(),
        default => (function () {
            http_response_code(404);
            view('home/not_found', ['title' => 'Страница не найдена']);
        })(),
    };
} catch (PDOException) {
    http_response_code(500);
    view('home/error', ['title' => 'Ошибка базы данных']);
}

