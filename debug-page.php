<?php

declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

error_reporting(E_ALL);

echo '<!DOCTYPE html>';
echo '<html>';
echo '<head>';
echo '<meta charset="UTF-8">';
echo '<title>Balmed 18 Debug</title>';
echo '</head>';
echo '<body>';

echo '<h1>STEP 1: Basic PHP/HTML works.</h1>';

echo '<p>Loading store header...</p>';

try {
    require __DIR__ . '/includes/store_header.php';

    echo '<h2>STEP 2: store_header.php loaded successfully.</h2>';
} catch (Throwable $e) {
    echo '<h2>STORE HEADER ERROR</h2>';
    echo '<pre>';
    echo htmlspecialchars($e->getMessage());
    echo '</pre>';
    exit;
}

echo '<p>Loading store footer...</p>';

try {
    require __DIR__ . '/includes/store_footer.php';

    echo '<h2>STEP 3: store_footer.php loaded successfully.</h2>';
} catch (Throwable $e) {
    echo '<h2>STORE FOOTER ERROR</h2>';
    echo '<pre>';
    echo htmlspecialchars($e->getMessage());
    echo '</pre>';
}

echo '</body>';
echo '</html>';
