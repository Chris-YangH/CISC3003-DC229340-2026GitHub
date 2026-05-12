<?php
session_start();
require_once __DIR__ . '/php/config.php';
$notice = $_SESSION['notice'] ?? '';
unset($_SESSION['notice']);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Password Reset - Scenario C</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<main class="auth-layout">
    <section class="panel">
        <p class="eyebrow">Password reset</p>
        <h1>Request Reset Link</h1>
        <?php if ($notice): ?><div class="alert success"><?= htmlspecialchars($notice) ?></div><?php endif; ?>
        <form action="php/send_reset.php" method="post">
            <label for="email">Email address</label>
            <input id="email" name="email" type="email" required>
            <button type="submit">Send Reset Email</button>
        </form>
        <p><a href="login.php">Back to login</a></p>
    </section>
</main>
<footer><?= page_footer() ?></footer>
</body>
</html>
