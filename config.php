<?php
declare(strict_types=1);

session_start(); // must be here once, before using $_SESSION

$host = 'localhost';
$db   = 'u414147218_portfolio';    // exactly as shown in phpMyAdmin
$user = 'u414147218_admin0101';          // the MySQL user from Hostinger panel
$pass = 'Sysadmin123**#';   // that user's password

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try {
    $pdo = new PDO(
        $dsn,
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (Throwable $e) {
    http_response_code(500);
    echo 'Database connection error: ' . htmlspecialchars($e->getMessage());
    exit;
}
