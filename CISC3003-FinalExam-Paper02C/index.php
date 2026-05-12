<?php
session_start();
require_once __DIR__ . '/php/config.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scenario C - Account Service</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<main class="auth-layout">
    <section class="intro">
        <p class="eyebrow">Scenario C</p>
        <h1>Secure Account Service</h1>
        <p>Signup, login, Ajax email validation, activation email, password reset, logout, and user dashboard are included.</p>
        <div class="actions">
            <a class="button" href="register.php">Create Account</a>
            <a class="button secondary" href="login.php">Sign In</a>
        </div>
    </section>
</main>
<footer><?= page_footer() ?></footer>
<script src="js/script.js"></script>
</body>
</html>
