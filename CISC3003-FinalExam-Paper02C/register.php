<?php
session_start();
require_once __DIR__ . '/php/config.php';
$errors = $_SESSION['errors'] ?? [];
$notice = $_SESSION['notice'] ?? '';
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['notice'], $_SESSION['old']);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - Scenario C</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<main class="auth-layout">
    <section class="panel">
        <p class="eyebrow">Create account</p>
        <h1>Signup</h1>
        <?php if ($notice): ?><div class="alert success"><?= htmlspecialchars($notice) ?></div><?php endif; ?>
        <?php if ($errors): ?>
            <div class="alert error"><?php foreach ($errors as $error): ?><p><?= htmlspecialchars($error) ?></p><?php endforeach; ?></div>
        <?php endif; ?>
        <form id="registerForm" action="php/process_register.php" method="post" novalidate>
            <label for="name">Full name</label>
            <input id="name" name="name" type="text" maxlength="80" required value="<?= htmlspecialchars($old['name'] ?? '') ?>">

            <label for="email">Email address</label>
            <input id="email" name="email" type="email" required value="<?= htmlspecialchars($old['email'] ?? '') ?>">
            <small id="emailStatus">Email will be checked by Ajax.</small>

            <label for="password">Password</label>
            <input id="password" name="password" type="password" minlength="8" required>

            <label for="password_confirmation">Confirm password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" minlength="8" required>

            <button type="submit">Register</button>
        </form>
        <p><a href="login.php">Already have an account?</a></p>
    </section>
</main>
<footer><?= page_footer() ?></footer>
<script src="js/script.js"></script>
</body>
</html>
