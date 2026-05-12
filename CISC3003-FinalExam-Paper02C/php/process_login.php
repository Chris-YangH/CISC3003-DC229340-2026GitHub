<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login.php');
    exit;
}

$email = trim((string) filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
$password = (string) ($_POST['password'] ?? '');
$errors = [];

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
    $errors[] = 'Please enter your email and password.';
} else {
    $stmt = $conn->prepare('SELECT id, name, email, password_hash, is_active, created_at FROM users WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if (!$user || !password_verify($password, $user['password_hash'])) {
        $errors[] = 'Invalid login details.';
    } elseif ((int) $user['is_active'] !== 1) {
        $errors[] = 'Please activate your email address before logging in.';
    } else {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['created_at'] = $user['created_at'];
        header('Location: ../dashboard.php');
        exit;
    }
}

$_SESSION['errors'] = $errors;
header('Location: ../login.php');
exit;
?>
