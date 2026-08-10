<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Database Configuration
|--------------------------------------------------------------------------
|
| Local XAMPP:
|   DB_HOST     = 127.0.0.1
|   DB_NAME     = balmed18
|   DB_USER     = root
|   DB_PASS     = ''
|
| Render:
|   These values are supplied through Render environment variables.
|
*/

define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');
define('DB_NAME', getenv('DB_NAME') ?: 'balmed18');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');


/*
|--------------------------------------------------------------------------
| Application URL
|--------------------------------------------------------------------------
|
| Local XAMPP:
|   /balmed18
|
| Render:
|   Leave this as an empty string because the application
|   runs from the domain root.
|
*/

define('BASE_URL', getenv('BASE_URL') ?: '/balmed18');


/*
|--------------------------------------------------------------------------
| Store Configuration
|--------------------------------------------------------------------------
*/

define('DELIVERY_FEE', 200);
define('CURRENCY', 'KSh');


/*
|--------------------------------------------------------------------------
| Product Upload Configuration
|--------------------------------------------------------------------------
*/

define(
    'PRODUCT_UPLOAD_DIR',
    __DIR__ . '/../uploads/products/'
);

define(
    'PRODUCT_UPLOAD_URL',
    BASE_URL . '/uploads/products/'
);

define(
    'MAX_UPLOAD_SIZE',
    5 * 1024 * 1024
);


/*
|--------------------------------------------------------------------------
| Timezone
|--------------------------------------------------------------------------
*/

date_default_timezone_set('Africa/Nairobi');


/*
|--------------------------------------------------------------------------
| Session
|--------------------------------------------------------------------------
*/

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}