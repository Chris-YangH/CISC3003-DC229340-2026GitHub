<?php
session_start();
require_once __DIR__ . '/php/config.php';
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scenario B - Contact Form with PHPMailer</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<main class="shell">
    <section class="panel">
        <p class="eyebrow">Scenario B</p>
        <h1>Contact Form</h1>
        <p>The form uses browser validation, PHP validation, PHPMailer SMTP sending, debug capture, and PRG redirect.</p>

        <nav class="top-actions">
            <a class="button secondary" href="register.php">Register</a>
            <a class="button secondary" href="login.php">Login</a>
            <a class="button secondary" href="dashboard.php">Dashboard</a>
        </nav>

        <?php if ($errors): ?>
            <div class="alert error">
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="php/send_contact.php" method="post">
            <label for="name">Full name</label>
            <input id="name" name="name" type="text" maxlength="80" required value="<?= htmlspecialchars($old['name'] ?? '') ?>">

            <label for="email">Email address</label>
            <input id="email" name="email" type="email" required value="<?= htmlspecialchars($old['email'] ?? '') ?>">

            <label for="subject">Subject</label>
            <input id="subject" name="subject" type="text" maxlength="120" required value="<?= htmlspecialchars($old['subject'] ?? '') ?>">

            <label for="message">Message</label>
            <textarea id="message" name="message" rows="7" maxlength="1500" required><?= htmlspecialchars($old['message'] ?? '') ?></textarea>

            <button type="submit">Send Message</button>
        </form>
    </section>
</main>
<footer><?= page_footer() ?></footer>
<script src="js/script.js"></script>
</body>
</html>
