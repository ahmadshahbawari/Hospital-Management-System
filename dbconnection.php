<?php
/**
 * Modernized database connection for PHP 8.x + MySQL/MariaDB.
 *
 * XAMPP defaults:
 *   host: localhost
 *   user: root
 *   password: empty
 *   database: station
 *
 * If your MySQL root account has a password, set DB_PASSWORD below.
 */
declare(strict_types=1);

const DB_HOST = 'localhost';
const DB_USER = 'root';
const DB_PASSWORD = '';
const DB_NAME = 'station';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $con = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $con->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    exit(
        '<h2>Database connection failed</h2>' .
        '<p>Make sure MySQL is running in XAMPP and the <code>station</code> database exists.</p>' .
        '<p>For security, the raw database error is not displayed.</p>'
    );
}
?>
