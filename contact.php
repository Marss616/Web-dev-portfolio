<?php
declare(strict_types=1);
session_start();
header('Content-Type: application/json; charset=utf-8');

function respond(bool $ok, string $message): void {
    http_response_code($ok ? 200 : 400);
    echo json_encode($ok ? ['ok' => true, 'message' => $message] : ['ok' => false, 'error' => $message]);
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    respond(false, 'Method not allowed.');
}

if (!isset($_SESSION['csrf'], $_POST['csrf']) || !hash_equals($_SESSION['csrf'], (string)($_POST['csrf'] ?? ''))) {
    respond(false, 'Security check failed.');
}

if (!empty($_POST['website'] ?? '')) {
    respond(true, 'Thanks.');
}

$name = trim((string)($_POST['name'] ?? ''));
$email = trim((string)($_POST['email'] ?? ''));
$message = trim((string)($_POST['message'] ?? ''));

if (mb_strlen($name) < 2) {
    respond(false, 'Please enter your name.');
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respond(false, 'Please enter a valid email address.');
}
if (mb_strlen($message) < 10) {
    respond(false, 'Please enter a longer message.');
}

$to = getenv('PORTFOLIO_TO_EMAIL') ?: 'jack.bell.work@outlook.com';
$subject = 'Portfolio contact form message';
$body = "Name: {$name}\nEmail: {$email}\n\nMessage:\n{$message}\n";
$headers = [
    'From: noreply@jackbellportfolio.com',
    'Reply-To: ' . $email,
    'Content-Type: text/plain; charset=UTF-8'
];

$sent = @mail($to, $subject, $body, implode("\r\n", $headers));

if (!$sent) {
    respond(false, 'Mail is not configured on this server yet.');
}

respond(true, 'Message sent.');
