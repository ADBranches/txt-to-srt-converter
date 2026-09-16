<?php

declare(strict_types=1);

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$publicDirectory = __DIR__;
$requestedFile = $publicDirectory . $path;

if ($path !== '/' && is_file($requestedFile)) {
    return false;
}

require $publicDirectory . '/index.php';
