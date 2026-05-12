<?php
declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

function send_app_mail(string $to, string $subject, string $body): array
{
    $autoload = __DIR__ . '/../vendor/autoload.php';
    if (!file_exists($autoload)) {
        return [
            'sent' => false,
            'debug' => 'PHPMailer is not installed. Run: composer require phpmailer/phpmailer',
        ];
    }

    require_once $autoload;
    $debug = '';

    try {
        $mail = new PHPMailer(true);
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->Debugoutput = static function (string $str, int $level) use (&$debug): void {
            $debug .= '[' . $level . '] ' . $str . "\n";
        };
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = SMTP_PORT;
        $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
        $mail->addAddress($to);
        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->send();

        return ['sent' => true, 'debug' => $debug];
    } catch (Throwable $e) {
        return ['sent' => false, 'debug' => $debug . 'Error: ' . $e->getMessage()];
    }
}
?>
