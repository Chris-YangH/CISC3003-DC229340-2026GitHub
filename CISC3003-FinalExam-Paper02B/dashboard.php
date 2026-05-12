<?php
session_start();
require_once __DIR__ . '/php/config.php';
$result = $_SESSION['result'] ?? null;
unset($_SESSION['result']);
$loggedIn = isset($_SESSION['user_id']);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scenario B - Mail Result</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
<main class="shell">
    <section class="panel">
        <p class="eyebrow">Scenario B Dashboard</p>
        <h1><?= $loggedIn ? 'Welcome, ' . htmlspecialchars($_SESSION['user_name']) : 'PHPMailer Contact Result' ?></h1>
        <?php if ($loggedIn): ?>
            <div class="alert success">You became a user on <?= htmlspecialchars($_SESSION['created_at']) ?>.</div>
        <?php endif; ?>
        <?php if ($result): ?>
            <div class="alert <?= $result['status'] === 'sent' ? 'success' : 'error' ?>">
                Message database ID <?= (int) $result['id'] ?>: <?= htmlspecialchars(strtoupper($result['status'])) ?>
            </div>
            <h2>SMTP debug output</h2>
            <pre><?= htmlspecialchars($result['debug'] ?: 'No debug output was produced.') ?></pre>
        <?php else: ?>
            <div class="alert">No message was submitted in this session.</div>
        <?php endif; ?>
        <p>Install PHPMailer in this project folder with <code>composer require phpmailer/phpmailer</code>, then edit SMTP settings in <code>php/config.php</code>.</p>
        <a class="button" href="index.php">Back to contact form</a>
        <?php if ($loggedIn): ?>
            <a class="button secondary" href="logout.php">Logout</a>
        <?php else: ?>
            <a class="button secondary" href="login.php">Login</a>
        <?php endif; ?>
    </section>
</main>
<footer><?= page_footer() ?></footer>
</body>
</html>
