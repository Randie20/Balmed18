<?php

declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

error_reporting(E_ALL);

require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Header Debug';

echo '<!DOCTYPE html>';
echo '<html lang="en">';
echo '<head>';

echo '<meta charset="UTF-8">';
echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';

echo '<title>Header Debug</title>';

echo '<p>1. Basic HTML works.</p>';

try {
    echo '<p>2. Testing e()...</p>';

    echo e('Balmed 18');

    echo '<p>3. e() works.</p>';
} catch (Throwable $e) {
    echo '<h2>e() ERROR</h2>';
    echo '<pre>';
    echo htmlspecialchars($e->getMessage());
    echo '</pre>';
    exit;
}

try {
    echo '<p>4. Testing url()...</p>';

    echo url('index.php');

    echo '<p>5. url() works.</p>';
} catch (Throwable $e) {
    echo '<h2>url() ERROR</h2>';
    echo '<pre>';
    echo htmlspecialchars($e->getMessage());
    echo '</pre>';
    exit;
}

echo '</head>';

echo '<body>';

echo '<h1>6. Body rendering works.</h1>';

echo '<header class="site-header">';
echo '<div class="container nav-wrap">';

echo '<a class="brand" href="' . e(url('index.php')) . '">';
echo '<span class="brand-mark">18</span>';
echo '<span>';
echo '<strong>Balmed 18</strong>';
echo '<small>Handcrafted in Kenya</small>';
echo '</span>';
echo '</a>';

echo '<nav class="main-nav">';

echo '<a href="' . e(url('index.php')) . '">Home</a>';
echo '<a href="' . e(url('products.php')) . '">Shop</a>';
echo '<a href="' . e(url('index.php#story')) . '">Our story</a>';

echo '<span>';
echo 'Cart ';
echo cart_count();
echo '</span>';

echo '</nav>';

echo '</div>';
echo '</header>';

echo '<h2>7. Header rendering completed.</h2>';

echo '</body>';
echo '</html>';