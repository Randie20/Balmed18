<?php

declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

error_reporting(E_ALL);

require_once __DIR__ . '/includes/functions.php';

echo '<!DOCTYPE html>';
echo '<html lang="en">';
echo '<head>';
echo '<meta charset="UTF-8">';
echo '<title>Cart Debug</title>';
echo '</head>';
echo '<body>';

echo '<h1>Cart Debug</h1>';

echo '<p>Functions loaded successfully.</p>';

echo '<p>Testing cart_count()...</p>';

try {
    $count = cart_count();

    echo '<p>cart_count() works.</p>';
    echo '<p>Cart count: ' . (int) $count . '</p>';
} catch (Throwable $e) {
    echo '<h2>cart_count() ERROR</h2>';

    echo '<pre>';
    echo htmlspecialchars(
        $e->getMessage(),
        ENT_QUOTES,
        'UTF-8'
    );
    echo '</pre>';

    echo '<p>File: ' . htmlspecialchars($e->getFile()) . '</p>';
    echo '<p>Line: ' . (int) $e->getLine() . '</p>';
}

echo '</body>';
echo '</html>';