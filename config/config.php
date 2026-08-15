<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Database Configuration
|--------------------------------------------------------------------------
|
| Production values are supplied through Render environment variables.
| Local development falls back to the local XAMPP MySQL configuration.
|
*/

define(
    'DB_HOST',
    getenv('DB_HOST') ?: '127.0.0.1'
);

define(
    'DB_PORT',
    getenv('DB_PORT') ?: '3306'
);

define(
    'DB_NAME',
    getenv('DB_NAME') ?: 'balmed18'
);

define(
    'DB_USER',
    getenv('DB_USER') ?: 'root'
);

define(
    'DB_PASS',
    getenv('DB_PASS') ?: ''
);


/*
|--------------------------------------------------------------------------
| Application Configuration
|--------------------------------------------------------------------------
*/

define(
    'CURRENCY',
    getenv('CURRENCY') ?: 'KES'
);

define(
    'BASE_URL',
    getenv('BASE_URL') ?: 'http://localhost/balmed18'
);


/*
|--------------------------------------------------------------------------
| Delivery Configuration
|--------------------------------------------------------------------------
|
| Set DELIVERY_FEE in Render environment variables if you want a
| different production delivery charge.
|
| Example:
| DELIVERY_FEE=200
|
*/

define(
    'DELIVERY_FEE',
    (float) (getenv('DELIVERY_FEE') ?: 0)
);


/*
|--------------------------------------------------------------------------
| Upload Configuration
|--------------------------------------------------------------------------
|
| Maximum product image size: 5MB
|
*/

define(
    'MAX_UPLOAD_SIZE',
    5 * 1024 * 1024
);


/*
|--------------------------------------------------------------------------
| Product Upload Directory
|--------------------------------------------------------------------------
|
| Product images are stored inside:
|
| uploads/products/
|
*/

define(
    'PRODUCT_UPLOAD_DIR',
    dirname(__DIR__) . '/uploads/products/'
);