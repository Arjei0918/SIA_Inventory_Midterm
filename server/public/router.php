<?php
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$file = __DIR__ . $path;

if ($path === '/docs' || $path === '/docs/') {
    readfile(__DIR__ . '/docs/index.html');
    exit;
}

if ($path !== '/' && is_file($file)) {
    return false;
}

require __DIR__ . '/index.php';
