<?php
declare(strict_types=1);

const DB_HOST = 'localhost';
const DB_USER = 'root';
const DB_PASS = '';
const DB_NAME = 'cisc3003_paper02c';

const SITE_OWNER_NAME = 'Yang Hao';
const SITE_STUDENT_ID = 'DC229340';
const SITE_YEAR = '2026';
const BASE_URL = 'http://localhost/CISC3003-FinalExam-Paper02C';

const SMTP_HOST = 'smtp.gmail.com';
const SMTP_PORT = 587;
const SMTP_USERNAME = 'your_gmail_address@gmail.com';
const SMTP_PASSWORD = 'your_gmail_app_password';
const MAIL_FROM = 'your_gmail_address@gmail.com';
const MAIL_FROM_NAME = 'CISC3003 Account Service';

function page_footer(): string
{
    return 'CISC3003 Web Programming: ' . SITE_OWNER_NAME . ' + ' . SITE_STUDENT_ID . ' + ' . SITE_YEAR;
}

function is_logged_in(): bool
{
    return isset($_SESSION['user_id']);
}

function require_login(): void
{
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}
?>
