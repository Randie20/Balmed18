<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
        DB_HOST,
        DB_PORT,
        DB_NAME
    );

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    /*
     * Aiven requires an SSL connection.
     *
     * The CA certificate is stored in the project root:
     *
     * Balmed18/
     * ├── ca.pem
     * ├── config/
     * │   ├── config.php
     * │   └── database.php
     * └── ...
     *
     * Therefore, from this directory we move one level up
     * to locate ca.pem.
     */
    if (DB_HOST !== '127.0.0.1' && DB_HOST !== 'localhost') {
        $caPath = dirname(__DIR__) . '/ca.pem';

        if (!file_exists($caPath)) {
            throw new RuntimeException(
                'Aiven CA certificate not found: ' . $caPath
            );
        }

        $options[PDO::MYSQL_ATTR_SSL_CA] = $caPath;
        $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = true;
    }

    $pdo = new PDO(
        $dsn,
        DB_USER,
        DB_PASS,
        $options
    );

    return $pdo;
}