<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../connect.php';

$name = trim((string) filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS));
$email = trim((string) filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
$password = (string) ($_POST['password'] ?? '');
$errors = [];

if ($name === '' || strlen($name) > 80) {
    $errors[] = 'Please enter your full name.';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address.';
}
if (strlen($password) < 8) {
    $errors[] = 'Password must be at least 8 characters.';
}

$stmt = $conn->prepare('SELECT id FROM users WHERE email = ?');
$stmt->bind_param('s', $email);
$stmt->execute();
if ($stmt->get_result()->num_rows > 0) {
    $errors[] = 'This email is already registered.';
}

if ($errors) {
    $_SESSION['errors'] = $errors;
    header('Location: ../register.php');
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare('INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)');
$stmt->bind_param('sss', $name, $email, $hash);
$stmt->execute();

$_SESSION['notice'] = 'Registration completed. Please login.';
header('Location: ../login.php');
exit;
?>
