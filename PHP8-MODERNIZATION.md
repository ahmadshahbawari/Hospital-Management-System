# Hospital Management System — PHP 8.x modernization

## Recommended environment
- PHP 8.2 or 8.3
- MySQL 8.x or a current MariaDB
- Apache 2.4+
- XAMPP (current release)

## Installation
1. Copy this folder into `xampp/htdocs/`.
2. Start Apache and MySQL.
3. Open `http://localhost/phpmyadmin/`.
4. Create a database named `station`.
5. Import `database/station.sql`.
6. If your MySQL `root` account has a password, edit `dbconnection.php` and set `DB_PASSWORD`.
7. Open the project URL in your browser.

## Demo accounts from the supplied SQL
- Admin: `admin` / `adminadmin`
- Pharmacy manager: `amina` / `aminaamina`
- Doctor: `doctoramina` / `123456789`

## What was modernized
- Replaced the old DB connection with `mysqli` and explicit UTF-8 (`utf8mb4`).
- Enabled PHP strict typing for the new bootstrap/connection/logout files.
- Added safer session cleanup in `logout.php`.
- Preserved the original PHP files in `_legacy_backup/`.

## Important
This is a compatibility modernization, not a security certification. The legacy application still contains direct SQL construction and plaintext password storage in its original pages/database schema. Do not use it for real patient or payment-card data without a larger security rewrite.
