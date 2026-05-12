<?php
session_start();
require_once __DIR__ . '/php/config.php';
require_login();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Scenario C</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
<main class="dashboard">
    <section class="summary">
        <p class="eyebrow">Scenario C Dashboard</p>
        <h1>Hello, <?= htmlspecialchars($_SESSION['user_name']) ?></h1>
        <p>You became a user on <?= htmlspecialchars((string) $_SESSION['created_at']) ?>.</p>
        <a class="button danger" href="logout.php">Logout</a>
    </section>
    <section class="services">
        <article>
            <h2>Profile Service</h2>
            <p>Email: <?= htmlspecialchars($_SESSION['user_email']) ?></p>
            <button class="button service-toggle" type="button" data-target="profileDetails">Show Details</button>
            <div id="profileDetails" class="service-panel" hidden>
                <p>Name: <?= htmlspecialchars($_SESSION['user_name']) ?></p>
                <p>User ID: <?= (int) $_SESSION['user_id'] ?></p>
                <label class="switch"><input type="checkbox" checked> Allow profile email notices</label>
            </div>
        </article>
        <article>
            <h2>Security Service</h2>
            <p>Password reset and account activation are controlled by secure email tokens.</p>
            <a class="button" href="forgot_password.php">Reset Password</a>
            <label class="switch"><input type="checkbox" checked> Keep login protection enabled</label>
        </article>
        <article>
            <h2>Course Services</h2>
            <p>Protected course tools are visible only after successful login.</p>
            <button class="button service-toggle" type="button" data-target="courseTools">Manage Services</button>
            <div id="courseTools" class="service-panel" hidden>
                <label class="switch"><input type="checkbox" checked> Weekly PHP practice reminders</label>
                <label class="switch"><input type="checkbox"> MySQL revision checklist</label>
                <label class="switch"><input type="checkbox" checked> Final exam screenshot tracker</label>
            </div>
        </article>
    </section>
</main>
<footer><?= page_footer() ?></footer>
<script src="js/script.js"></script>
</body>
</html>
