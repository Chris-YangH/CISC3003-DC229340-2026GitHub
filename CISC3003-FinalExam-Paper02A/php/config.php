<?php
declare(strict_types=1);

const DB_HOST = 'localhost';
const DB_USER = 'root';
const DB_PASS = '';
const DB_NAME = 'cisc3003_paper02a';

const SITE_OWNER_NAME = 'Yang Hao';
const SITE_STUDENT_ID = 'DC229340';
const SITE_YEAR = '2026';

function page_footer(): string
{
    return 'CISC3003 Web Programming: ' . SITE_OWNER_NAME . ' + ' . SITE_STUDENT_ID . ' + ' . SITE_YEAR;
}
?>
