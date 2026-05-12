<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../connect.php';
require_once __DIR__ . '/mailer.php';

$email = trim((string) filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));

if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $stmt = $conn->prepare('SELECT id, name FROM users WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user) {
        $token = bin2hex(random_bytes(32));
        $hash = hash('sha256', $token);
        $expires = date('Y-m-d H:i:s', time() + 1800);
        $stmt = $conn->prepare('UPDATE users SET reset_hash = ?, reset_expires_at = ? WHERE id = ?');
        $stmt->bind_param('ssi', $hash, $expires, $user['id']);
        $stmt->execute();

        $link = BASE_URL . '/reset_password.php?token=' . urlencode($token);
        $mail = send_app_mail($email, 'Reset your CISC3003 password', "Hello {$user['name']},\n\nReset your password within 30 minutes:\n{$link}");
        if (!$mail['sent']) {
            $_SESSION['notice'] = 'Local testing reset link: ' . $link . ' Debug: ' . $mail['debug'];
            header('Location: ../forgot_password.php');
            exit;
        }
    }
}

$_SESSION['notice'] = 'If this email exists, a password reset link has been sent.';
header('Location: ../forgot_password.php');
exit;
?>
