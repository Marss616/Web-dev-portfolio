<?php
// server/csrf.php
declare(strict_types=1);
session_start();
header('Content-Type: application/json; charset=utf-8');
if (!isset($_SESSION['csrf'])) {
  $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
echo json_encode(['token' => $_SESSION['csrf']]);
