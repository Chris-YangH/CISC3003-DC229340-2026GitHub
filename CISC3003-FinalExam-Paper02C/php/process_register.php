<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../connect.php';
require_once __DIR__ . '/mailer.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../register.php');
    exit;
}

$name = trim((string) filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS));
$email = trim((string) filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
$password = (string) ($_POST['password'] ?? '');
$confirmation = (string) ($_POST['password_confirmation'] ?? '');
$errors = [];

if ($name === '' || strlen($name) > 80) {
    $errors[] = 'Please enter a valid name.';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email.';
}
if (strlen($password) < 8) {
    $errors[] = 'Password must be at least 8 characters.';
}
if ($password !== $confirmation) {
    $errors[] = 'Password confirmation does not match.';
}

$stmt = $conn->prepare('SELECT id FROM users WHERE email = ?');
$stmt->bind_param('s', $email);
$stmt->execute();
if ($stmt->get_result()->num_rows > 0) {
    $errors[] = 'This email address is already registered.';
}

if ($errors) {
    $_SESSION['errors'] = $errors;
    $_SESSION['old'] = ['name' => $name, 'email' => $email];
    header('Location: ../register.php');
    exit;
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);
$activationToken = bin2hex(random_bytes(32));
$activationHash = hash('sha256', $activationToken);

$stmt = $conn->prepare('INSERT INTO users (name, email, password_hash, activation_hash) VALUES (?, ?, ?, ?)');
$stmt->bind_param('ssss', $name, $email, $passwordHash, $activationHash);
$stmt->execute();

$activationLink = BASE_URL . '/activate.php?token=' . urlencode($activationToken);
$mail = send_app_mail($email, 'Activate your CISC3003 account', "Hello {$name},\n\nActivate your account:\n{$activationLink}");

$_SESSION['notice'] = $mail['sent']
    ? 'Registration saved. Please check your email to activate your account.'
    : 'Registration saved. Email was not sent, so use this activation link for local testing: ' . $activationLink . ' Debug: ' . $mail['debug'];
header('Location: ../login.php');
exit;
?>
