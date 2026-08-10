<?php
declare(strict_types=1);

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'balmed18');
define('DB_USER', 'root');
define('DB_PASS', '');

define('BASE_URL', '/balmed18');

define('DELIVERY_FEE', 200);
define('CURRENCY', 'KSh');

define('PRODUCT_UPLOAD_DIR', __DIR__ . '/../uploads/products/');
define('PRODUCT_UPLOAD_URL', BASE_URL . '/uploads/products/');
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024);

date_default_timezone_set('Africa/Nairobi');

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
