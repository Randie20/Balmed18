<?php

declare(strict_types=1);

$host = 'mysql-17539253-balmed18.d.aivencloud.com';
$port = 23487;
$dbname = 'balmed18';
$username = 'avnadmin';
$password = 'YOUR_AIVEN_PASSWORD';

try {
    $pdo = new PDO(
        "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,

            PDO::MYSQL_ATTR_SSL_CA => __DIR__ . '/../ca.pem',
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => true,
        ]
    );
} catch (PDOException $e) {
    die('Database connection failed.');
}