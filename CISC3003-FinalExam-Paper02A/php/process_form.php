<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

$name = trim((string) filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS));
$email = trim((string) filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
$phone = trim((string) filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_SPECIAL_CHARS));
$topic = trim((string) filter_input(INPUT_POST, 'topic', FILTER_SANITIZE_SPECIAL_CHARS));
$message = trim((string) filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS));
$study_mode = trim((string) filter_input(INPUT_POST, 'study_mode', FILTER_SANITIZE_SPECIAL_CHARS));
$subscribe = filter_input(INPUT_POST, 'subscribe', FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ? 1 : 0;
$skills = $_POST['skills'] ?? [];

$allowed_topics = ['php', 'mysql', 'security', 'frontend'];
$allowed_modes = ['online', 'onsite', 'hybrid'];
$allowed_skills = ['html', 'css', 'javascript', 'php', 'mysql'];
$skills = array_values(array_intersect($allowed_skills, array_map('strval', (array) $skills)));

$errors = [];
if ($name === '' || strlen($name) > 80) {
    $errors[] = 'Please enter a name up to 80 characters.';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address.';
}
if ($phone !== '' && !preg_match('/^[0-9+\\-\\s]{6,20}$/', $phone)) {
    $errors[] = 'Please enter a valid phone number.';
}
if (!in_array($topic, $allowed_topics, true)) {
    $errors[] = 'Please choose a valid topic.';
}
if ($message === '' || strlen($message) > 1000) {
    $errors[] = 'Please enter a message up to 1000 characters.';
}
if (!in_array($study_mode, $allowed_modes, true)) {
    $errors[] = 'Please choose a study mode.';
}

if ($errors) {
    $_SESSION['errors'] = $errors;
    $_SESSION['old'] = $_POST;
    header('Location: ../index.php');
    exit;
}

// Prepared statements prevent SQL injection because user input is bound as data.
$sql = 'INSERT INTO enquiries (name, email, phone, topic, message, study_mode, skills, subscribe)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
$stmt = $conn->prepare($sql);
$skills_csv = implode(',', $skills);
$stmt->bind_param('sssssssi', $name, $email, $phone, $topic, $message, $study_mode, $skills_csv, $subscribe);
$stmt->execute();

$_SESSION['success'] = 'The form was validated and inserted with a prepared statement. New record ID: ' . $stmt->insert_id;
header('Location: ../index.php');
exit;
?>
