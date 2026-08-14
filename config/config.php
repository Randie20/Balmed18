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