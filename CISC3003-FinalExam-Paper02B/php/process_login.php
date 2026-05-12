<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../connect.php';

$email = trim((string) filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
$password = (string) ($_POST['password'] ?? '');
$stmt = $conn->prepare('SELECT id, name, email, password_hash, created_at FROM users WHERE email = ?');
$stmt->bind_param('s', $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user || !password_verify($password, $user['password_hash'])) {
    $_SESSION['errors'] = ['Invalid email or password.'];
    header('Location: ../login.php');
    exit;
}

session_regenerate_id(true);
$_SESSION['user_id'] = (int) $user['id'];
$_SESSION['user_name'] = $user['name'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['created_at'] = $user['created_at'];
header('Location: ../dashboard.php');
exit;
?>
