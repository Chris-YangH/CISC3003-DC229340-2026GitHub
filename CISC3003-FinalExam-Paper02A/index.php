<?php
session_start();
require_once __DIR__ . '/php/config.php';
$errors = $_SESSION['errors'] ?? [];
$success = $_SESSION['success'] ?? '';
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['success'], $_SESSION['old']);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scenario A - Form and MySQL Insert</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<main class="shell">
    <section class="panel">
        <p class="eyebrow">Scenario A</p>
        <h1>Student Enquiry Form</h1>
        <p>This form demonstrates HTML form controls, PHP filtering, SQL injection prevention, and prepared INSERT statements.</p>

        <?php if ($errors): ?>
            <div class="alert error">
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <nav class="top-actions">
            <a class="button secondary" href="register.php">Register</a>
            <a class="button secondary" href="login.php">Login</a>
            <a class="button secondary" href="dashboard.php">Dashboard</a>
        </nav>

        <form action="php/process_form.php" method="post" novalidate>
            <label for="name">Full name</label>
            <input id="name" name="name" type="text" maxlength="80" required value="<?= htmlspecialchars($old['name'] ?? '') ?>">

            <label for="email">Email address</label>
            <input id="email" name="email" type="email" required value="<?= htmlspecialchars($old['email'] ?? '') ?>">

            <label for="phone">Phone number</label>
            <input id="phone" name="phone" type="text" placeholder="+853 6000 0000" value="<?= htmlspecialchars($old['phone'] ?? '') ?>">

            <label for="topic">Interested topic</label>
            <select id="topic" name="topic" required>
                <option value="">Choose one</option>
                <?php foreach (['php' => 'PHP', 'mysql' => 'MySQL', 'security' => 'Web Security', 'frontend' => 'Front-end'] as $value => $label): ?>
                    <option value="<?= $value ?>" <?= (($old['topic'] ?? '') === $value) ? 'selected' : '' ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>

            <fieldset>
                <legend>Preferred study mode</legend>
                <?php foreach (['online' => 'Online', 'onsite' => 'On-site', 'hybrid' => 'Hybrid'] as $value => $label): ?>
                    <label class="choice"><input type="radio" name="study_mode" value="<?= $value ?>" required <?= (($old['study_mode'] ?? '') === $value) ? 'checked' : '' ?>> <?= $label ?></label>
                <?php endforeach; ?>
            </fieldset>

            <fieldset>
                <legend>Skills already learned</legend>
                <?php foreach (['html' => 'HTML', 'css' => 'CSS', 'javascript' => 'JavaScript', 'php' => 'PHP', 'mysql' => 'MySQL'] as $value => $label): ?>
                    <label class="choice"><input type="checkbox" name="skills[]" value="<?= $value ?>" <?= in_array($value, (array)($old['skills'] ?? []), true) ? 'checked' : '' ?>> <?= $label ?></label>
                <?php endforeach; ?>
            </fieldset>

            <label for="message">Message</label>
            <textarea id="message" name="message" rows="6" maxlength="1000" required><?= htmlspecialchars($old['message'] ?? '') ?></textarea>

            <label class="choice"><input type="checkbox" name="subscribe" value="1" <?= !empty($old['subscribe']) ? 'checked' : '' ?>> Subscribe to course updates</label>

            <button type="submit">Submit Enquiry</button>
        </form>
    </section>
</main>
<footer><?= page_footer() ?></footer>
<script src="js/script.js"></script>
</body>
</html>
