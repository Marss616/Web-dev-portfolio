<?php
declare(strict_types=1);
session_start();
header('Content-Type: application/json; charset=utf-8');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php'; // Composer autoload

// --- Config (edit) ---
$TO        = 'contact@mars616.com';         // where you receive
$FROM      = 'contact@mars616.com';         // must be your Hostinger mailbox
$SUBJECT   = 'New message from portfolio';
$SMTP_HOST = 'smtp.hostinger.com';
$SMTP_USER = 'contact@mars616.com';
$SMTP_PASS = getenv('SMTP_PASS') ?: 'CHANGE_ME_NOW'; // set in hPanel or replace with real value
$SMTP_PORT = 587; // 587 (TLS) or 465 (SSL)

// --- Helper ---
function respond(bool $ok, string $msg = ''): void {
  http_response_code($ok ? 200 : 400);
  echo json_encode($ok ? ['ok' => true] : ['ok' => false, 'error' => $msg]);
  exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
  respond(false, 'Method not allowed.');
}

// Basic IP rate limit (1 per 30s)
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$bucket = sys_get_temp_dir() . '/contact-rate-' . md5($ip);
$now = time();
$last = is_file($bucket) ? (int)file_get_contents($bucket) : 0;
if ($now - $last < 30) {
  respond(false, 'Please wait a bit before trying again.');
}
file_put_contents($bucket, (string)$now);

// CSRF
if (!isset($_SESSION['csrf'], $_POST['csrf']) || !hash_equals($_SESSION['csrf'], (string)$_POST['csrf'])) {
  respond(false, 'Security check failed.');
}

// Honeypot
if (!empty($_POST['website'])) {
  respond(true, 'Thanks.');
}

// Validate
$name    = trim((string)($_POST['name'] ?? ''));
$email   = trim((string)($_POST['email'] ?? ''));
$message = trim((string)($_POST['message'] ?? ''));
if (strlen($name) < 2)                          respond(false, 'Please enter your name.');
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) respond(false, 'Please enter a valid email.');
if (strlen($message) < 10)                      respond(false, 'Please write a longer message.');

// Build plain-text body
$ua  = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
$ref = $_SERVER['HTTP_REFERER'] ?? 'unknown';
$body = "New contact form submission\n\nName: $name\nEmail: $email\n\nMessage:\n$message\n\nIP: $ip\nUA: $ua\nReferrer: $ref\n";

try {
  $mail = new PHPMailer(true);
  if ($SMTP_PORT === 465) {
    $mail->isSMTP();
    $mail->Host       = $SMTP_HOST;
    $mail->SMTPAuth   = true;
    $mail->Username   = $SMTP_USER;
    $mail->Password   = $SMTP_PASS;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;
  } else {
    $mail->isSMTP();
    $mail->Host       = $SMTP_HOST;
    $mail->SMTPAuth   = true;
    $mail->Username   = $SMTP_USER;
    $mail->Password   = $SMTP_PASS;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
  }

  $mail->setFrom($FROM, 'Portfolio');
  $mail->addAddress($TO);
  $mail->addReplyTo($email, $name);

  $mail->isHTML(false);
  $mail->Subject = $SUBJECT;
  $mail->Body    = $body;

  $mail->send();
  respond(true, 'Sent');
} catch (Exception $e) {
  respond(false, 'Mailer error: ' . $e->getMessage());
}