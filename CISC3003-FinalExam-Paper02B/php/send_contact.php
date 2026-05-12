<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../connect.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

$name = trim((string) filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS));
$email = trim((string) filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
$subject = trim((string) filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_SPECIAL_CHARS));
$message = trim((string) filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS));

$errors = [];
if ($name === '' || strlen($name) > 80) {
    $errors[] = 'Please enter your name.';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address.';
}
if ($subject === '' || strlen($subject) > 120) {
    $errors[] = 'Please enter a subject.';
}
if ($message === '' || strlen($message) > 1500) {
    $errors[] = 'Please enter a message up to 1500 characters.';
}

if ($errors) {
    $_SESSION['errors'] = $errors;
    $_SESSION['old'] = $_POST;
    header('Location: ../index.php');
    exit;
}

$stmt = $conn->prepare('INSERT INTO contact_messages (name, email, subject, message, mail_status, debug_info) VALUES (?, ?, ?, ?, ?, ?)');
$mailStatus = 'not_sent';
$debugInfo = '';

try {
    $autoload = __DIR__ . '/../vendor/autoload.php';
    if (!file_exists($autoload)) {
        throw new RuntimeException('PHPMailer is not installed. Run: composer require phpmailer/phpmailer');
    }
    require $autoload;

    $mail = new PHPMailer(true);
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->Debugoutput = static function (string $str, int $level) use (&$debugInfo): void {
        $debugInfo .= '[' . $level . '] ' . $str . "\n";
    };

    $mail->isSMTP();
    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USERNAME;
    $mail->Password = SMTP_PASSWORD;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = SMTP_PORT;

    $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
    $mail->addAddress(MAIL_TO);
    $mail->addReplyTo($email, $name);
    $mail->isHTML(false);
    $mail->Subject = '[CISC3003 Contact] ' . $subject;
    $mail->Body = "Name: {$name}\nEmail: {$email}\n\n{$message}";
    $mail->send();
    $mailStatus = 'sent';
} catch (Throwable $e) {
    $mailStatus = 'failed';
    $debugInfo .= 'Error: ' . $e->getMessage();
}

$stmt->bind_param('ssssss', $name, $email, $subject, $message, $mailStatus, $debugInfo);
$stmt->execute();

$_SESSION['result'] = [
    'status' => $mailStatus,
    'debug' => $debugInfo,
    'id' => $stmt->insert_id,
];

// Post/Redirect/Get prevents duplicate email sends on browser refresh.
header('Location: ../dashboard.php');
exit;
?>
