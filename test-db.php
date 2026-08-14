<?php

declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

error_reporting(E_ALL);

require_once __DIR__ . '/config/database.php';

header('Content-Type: text/plain; charset=utf-8');

try {
    $pdo = db();

    $stmt = $pdo->query('SELECT DATABASE() AS database_name');

    $result = $stmt->fetch();

    echo "Database connection successful." . PHP_EOL;
    echo "Database: " . ($result['database_name'] ?? 'Unknown') . PHP_EOL;
} catch (Throwable $e) {
    http_response_code(500);

    echo "Database connection failed." . PHP_EOL;
    echo "Error: " . $e->getMessage() . PHP_EOL;
}
