<?php
session_start();
require_once __DIR__ . '/php/config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scenario A - Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
<main class="shell">
    <section class="panel">
        <p class="eyebrow">Scenario A Dashboard</p>
        <h1>Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></h1>
        <div class="alert success">You became a user on <?= htmlspecialchars($_SESSION['created_at']) ?>.</div>
        <h2>Tasks demonstrated</h2>
        <ul>
            <li>A.01-A.04: Best-practice HTML form, text input, textarea, select, radio, and checkbox controls.</li>
            <li>A.05-A.06: PHP form processing and validation with filter functions.</li>
            <li>A.07-A.08: SQL injection prevention using a prepared statement.</li>
            <li>A.09-A.10: Database/table creation and INSERT statement in db/database.sql.</li>
        </ul>
        <a class="button" href="index.php">Back to form</a>
        <a class="button secondary" href="logout.php">Logout</a>
    </section>
</main>
<footer><?= page_footer() ?></footer>
</body>
</html>
