<?php

declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

error_reporting(E_ALL);

header('Content-Type: text/plain; charset=utf-8');

echo "STEP 1: PHP is running." . PHP_EOL;

require_once __DIR__ . '/config/database.php';

echo "STEP 2: database.php loaded." . PHP_EOL;

try {
    $pdo = db();

    echo "STEP 3: database connection successful." . PHP_EOL;

    $stmt = $pdo->query('SELECT COUNT(*) AS total FROM products');

    $result = $stmt->fetch();

    echo "STEP 4: products query successful." . PHP_EOL;
    echo "Products: " . ($result['total'] ?? '0') . PHP_EOL;

} catch (Throwable $e) {

    echo PHP_EOL;
    echo "DATABASE ERROR:" . PHP_EOL;
    echo $e->getMessage() . PHP_EOL;

    exit;
}

echo PHP_EOL;
echo "STEP 5: Loading functions.php..." . PHP_EOL;

try {

    require_once __DIR__ . '/includes/functions.php';

    echo "STEP 6: functions.php loaded." . PHP_EOL;

} catch (Throwable $e) {

    echo PHP_EOL;
    echo "FUNCTIONS ERROR:" . PHP_EOL;
    echo $e->getMessage() . PHP_EOL;

    exit;
}

echo PHP_EOL;
echo "STEP 7: Testing product query..." . PHP_EOL;

try {

    $products = db()->query(
        'SELECT
            p.*,
            c.name AS category_name
         FROM products p
         LEFT JOIN categories c
            ON c.id = p.category_id
         WHERE p.is_active = 1
         ORDER BY p.created_at DESC
         LIMIT 6'
    )->fetchAll();

    echo "STEP 8: Product query successful." . PHP_EOL;
    echo "Products returned: " . count($products) . PHP_EOL;

} catch (Throwable $e) {

    echo PHP_EOL;
    echo "PRODUCT QUERY ERROR:" . PHP_EOL;
    echo $e->getMessage() . PHP_EOL;

    exit;
}

echo PHP_EOL;
echo "ALL TESTS PASSED." . PHP_EOL;