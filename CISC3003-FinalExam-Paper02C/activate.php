<?php
session_start();
require_once __DIR__ . '/connect.php';

$token = (string) ($_GET['token'] ?? '');
$hash = hash('sha256', $token);
$stmt = $conn->prepare('UPDATE users SET is_active = 1, activation_hash = NULL WHERE activation_hash = ?');
$stmt->bind_param('s', $hash);
$stmt->execute();

$_SESSION['notice'] = $stmt->affected_rows === 1
    ? 'Your email has been confirmed. You may now log in.'
    : 'Activation link is invalid or has already been used.';
header('Location: login.php');
exit;
?>
