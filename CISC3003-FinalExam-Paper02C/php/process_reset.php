<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../connect.php';

$token = (string) ($_POST['token'] ?? '');
$password = (string) ($_POST['password'] ?? '');
$confirmation = (string) ($_POST['password_confirmation'] ?? '');
$errors = [];

if (strlen($password) < 8) {
    $errors[] = 'Password must be at least 8 characters.';
}
if ($password !== $confirmation) {
    $errors[] = 'Password confirmation does not match.';
}

$hash = hash('sha256', $token);
$stmt = $conn->prepare('SELECT id FROM users WHERE reset_hash = ? AND reset_expires_at > NOW()');
$stmt->bind_param('s', $hash);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
if (!$user) {
    $errors[] = 'Password reset link is invalid or expired.';
}

if ($errors) {
    $_SESSION['errors'] = $errors;
    header('Location: ../reset_password.php?token=' . urlencode($token));
    exit;
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare('UPDATE users SET password_hash = ?, reset_hash = NULL, reset_expires_at = NULL WHERE id = ?');
$stmt->bind_param('si', $passwordHash, $user['id']);
$stmt->execute();

$_SESSION['notice'] = 'Password updated. Please log in.';
header('Location: ../login.php');
exit;
?>
