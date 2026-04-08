<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Controllers\RecordController;

// Поддерживаем GET и POST запросы (для форм)
if (in_array($_SERVER['REQUEST_METHOD'], ['GET', 'POST'], true)) {
    $controller = new RecordController();
    $controller->handleRequest();
} else {
    http_response_code(405);
    echo 'Метод запроса не поддерживается.';
}