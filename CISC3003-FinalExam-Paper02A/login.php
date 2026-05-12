<?php
session_start();
require_once __DIR__ . '/php/config.php';
$errors = $_SESSION['errors'] ?? [];
$notice = $_SESSION['notice'] ?? '';
unset($_SESSION['errors'], $_SESSION['notice']);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Scenario A</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<main class="shell">
    <section class="panel">
        <p class="eyebrow">Scenario A Account</p>
        <h1>Login</h1>
        <?php if ($notice): ?><div class="alert success"><?= htmlspecialchars($notice) ?></div><?php endif; ?>
        <?php if ($errors): ?><div class="alert error"><?php foreach ($errors as $error): ?><p><?= htmlspecialchars($error) ?></p><?php endforeach; ?></div><?php endif; ?>
        <form id="loginForm" action="php/process_login.php" method="post">
            <label for="email">Email address</label>
            <input id="email" name="email" type="email" required>
            <label for="password">Password</label>
            <input id="password" name="password" type="password" required>
            <button type="submit">Login</button>
        </form>
        <p><a href="register.php">Create a new account</a></p>
    </section>
</main>
<footer><?= page_footer() ?></footer>
<script src="js/script.js"></script>
</body>
</html>
