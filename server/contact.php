<?php
// server/contact.php
declare(strict_types=1);
session_start();
header('Content-Type: application/json; charset=utf-8');

// ===== Config =====
$TO        = 'contact@mars616.com'; // you receive here
$FROM      = 'contact@mars616.com'; // must be a mailbox you own on Hostinger
$SMTP_HOST = 'smtp.hostinger.com';
$SMTP_PORT = 587; // TLS (or 465 for SMTPS)
$SMTP_USER = 'contact@mars616.com';
$SMTP_PASS  = 'xasd123@#@#4431!!dF';

// ===== Helpers =====
function respond($ok, $msg) {
  http_response_code($ok ? 200 : 400);
  echo json_encode($ok ? ['ok' => true] : ['ok' => false, 'error' => $msg]);
  exit;
}

// Optional CORS (only if posting from a different origin)
if ($ALLOW_ORIGIN) {
  header("Access-Control-Allow-Origin: $ALLOW_ORIGIN");
  header('Vary: Origin');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  respond(false, 'Method not allowed.');
}

// Basic rate limiting (IP-based): 1 request / 30s
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$bucket = sys_get_temp_dir() . '/contact-rate-' . md5($ip);
$now = time();
$last = is_file($bucket) ? intval(file_get_contents($bucket)) : 0;
if ($now - $last < 30) {
  respond(false, 'Please wait a bit before trying again.');
}
file_put_contents($bucket, (string)$now);

// CSRF check
if (!isset($_SESSION['csrf']) || !isset($_POST['csrf']) || !hash_equals($_SESSION['csrf'], (string)$_POST['csrf'])) {
  respond(false, 'Security check failed.');
}

// Honeypot check
if (!empty($_POST['website'])) {
  respond(true, 'Thanks.'); // pretend success to confuse bots
}

// Validation & sanitisation
$name    = trim((string)($_POST['name'] ?? ''));
$email   = trim((string)($_POST['email'] ?? ''));
$message = trim((string)($_POST['message'] ?? ''));

if (strlen($name) < 2) respond(false, 'Please enter your name.');
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) respond(false, 'Please enter a valid email.');
if (strlen($message) < 10) respond(false, 'Please write a longer message.');

// Build safe plain-text email
$ua = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
$ref = $_SERVER['HTTP_REFERER'] ?? 'unknown';
$body = "New contact form submission\n\n"
      . "Name: {$name}\n"
      . "Email: {$email}\n"
      . "Message:\n{$message}\n\n"
      . "IP: {$ip}\nUser-Agent: {$ua}\nReferrer: {$ref}\n";

$headers = [
  'MIME-Version: 1.0',
  'Content-Type: text/plain; charset=UTF-8',
  'X-Mailer: PHP/' . phpversion(),
  'From: no-reply@' . ($_SERVER['SERVER_NAME'] ?? 'localhost')
];

$ok = @mail($TO, $SUBJECT, $body, implode("\r\n", $headers));

if ($ok) {
  respond(true, 'Sent');
} else {
  // If mail() fails on your host, consider PHPMailer/SMTP
  respond(false, 'Unable to send email on this server.');
}
