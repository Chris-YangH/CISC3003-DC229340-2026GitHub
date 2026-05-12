<?php
session_start();
require_once __DIR__ . '/php/config.php';
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - Scenario B</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<main class="shell">
    <section class="panel">
        <p class="eyebrow">Scenario B Account</p>
        <h1>Register</h1>
        <?php if ($errors): ?><div class="alert error"><?php foreach ($errors as $error): ?><p><?= htmlspecialchars($error) ?></p><?php endforeach; ?></div><?php endif; ?>
        <form id="registerForm" action="php/process_register.php" method="post">
            <label for="name">Full name</label>
            <input id="name" name="name" type="text" maxlength="80" required>
            <label for="email">Email address</label>
            <input id="email" name="email" type="email" required>
            <label for="password">Password</label>
            <input id="password" name="password" type="password" minlength="8" required>
            <button type="submit">Create Account</button>
        </form>
        <p><a href="login.php">Already registered? Login</a></p>
    </section>
</main>
<footer><?= page_footer() ?></footer>
<script src="js/script.js"></script>
</body>
</html>
