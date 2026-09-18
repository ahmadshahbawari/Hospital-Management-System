# Professional HMS 2026 Upgrade

## New features
- Single role-based login portal: Admin / Doctor / Patient.
- Offline English, Pashto and Dari interface translations (no CDN required).
- Dark / light mode stored in the browser.
- Hospital logo upload from **Hospital Settings**.
- Personal profile picture and password change.
- Patient accounts stored in the `patient` table with secure password hashes.
- Patient **My Health Record** page: personal information, treatment/diagnosis records and prescriptions.
- Admin financial dashboard: patient fees, doctor salaries, yearly totals and monthly chart.
- Doctor salary payment tracking.
- Finance & Fees module for patient fees and other income/expenses.
- Modern responsive dashboard and sidebar.

## Installation
1. Import `database/station.sql` into MySQL/MariaDB.
2. Import `database/professional_upgrade.sql` after it.
3. Put the project in `C:\xampp\htdocs\`.
4. Start Apache and MySQL in XAMPP.
5. Open the project once in the browser.
6. Use the central login portal; do not manually open module URLs.

## Test accounts
- Admin: `admin` / `adminadmin`
- Doctor: `doctoramina` / `123456789`
- Demo Patient: `patient1` / `patient123`

The application upgrades successful legacy plaintext passwords to secure PHP password hashes.

## Finance model
- Patient payments are represented by `financial_transactions` with type `patient_fee`.
- Doctor salary payments are stored in `salary_payments` and also recorded in `financial_transactions`.
- `doctor.salary` is the doctor's configured salary; `salary_payments` is the amount actually paid.

## Important
This upgrade is designed to work offline on XAMPP. It does not load Bootstrap, Chart.js, Google Fonts or other remote libraries for the new dashboard.
