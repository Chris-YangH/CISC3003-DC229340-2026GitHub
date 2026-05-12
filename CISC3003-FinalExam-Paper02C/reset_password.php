<?php
session_start();
require_once __DIR__ . '/php/config.php';
$token = (string) ($_GET['token'] ?? '');
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Choose New Password - Scenario C</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<main class="auth-layout">
    <section class="panel">
        <p class="eyebrow">Password reset</p>
        <h1>Choose New Password</h1>
        <?php if ($errors): ?><div class="alert error"><?php foreach ($errors as $error): ?><p><?= htmlspecialchars($error) ?></p><?php endforeach; ?></div><?php endif; ?>
        <form action="php/process_reset.php" method="post">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
            <label for="password">New password</label>
            <input id="password" name="password" type="password" minlength="8" required>
            <label for="password_confirmation">Confirm new password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" minlength="8" required>
            <button type="submit">Update Password</button>
        </form>
    </section>
</main>
<footer><?= page_footer() ?></footer>
</body>
</html>
