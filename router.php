<?php
declare(strict_types=1);

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$routes = [
    '/api/control/health' => __DIR__ . '/public/api/control/health.php',
    '/api/control/manifest' => __DIR__ . '/public/api/control/manifest.php',
    '/api/control/download' => __DIR__ . '/public/api/control/download.php',
    '/api/device/enroll' => __DIR__ . '/public/api/device/enroll.php',
];

if (isset($routes[$path])) {
    require $routes[$path];
    return true;
}

$publicFile = __DIR__ . '/public' . $path;
if ($path !== '/' && is_file($publicFile)) {
    return false;
}

require __DIR__ . '/public/index.php';
return true;
