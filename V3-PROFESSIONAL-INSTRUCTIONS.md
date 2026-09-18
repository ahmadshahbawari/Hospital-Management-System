# Hospital Management System — Professional v3

## Database import order
1. Create a MySQL database named `station`.
2. Import `database/station.sql`.
3. Import `database/professional_upgrade.sql`.
4. Import `database/professional_upgrade_v3.sql`.

Do not create a second database. All three SQL files belong to the same `station` database.

## New v3 features
- Offline English / Pashto / Dari language switch.
- RTL sidebar on the right for Pashto and Dari; LTR sidebar on the left for English.
- Dark/light mode.
- Hospital logo and professional settings.
- Worker/staff role with login and salary.
- Doctor and worker attendance: admin can mark attendance; doctors/workers can mark themselves.
- Monthly/yearly dashboard financial analytics with line chart.
- Patient fee and doctor salary tracking.
- Backup: download the entire MySQL database as a `.sql` file.
- Excel: download any database table as an Excel-compatible `.xls` file.
- Settings for hospital identity, language, timezone, currency, appointments, stock threshold, session timeout and GitHub repository information.
- Sidebar dropdown groups.
- Legacy modules are opened inside the central dashboard workspace instead of requiring users to type their URLs.

## Test accounts
- Admin: `admin` / `adminadmin`
- Doctor: `doctoramina` / `123456789`
- Patient: `patient1` / `patient123`
- Worker: `worker1` / `worker123`

## GitHub workflow
GitHub is appropriate for PHP/CSS/JS source code. It should not be used as the database backup system.

Before updating code:
1. Open Dashboard > Backup & Excel.
2. Download the full SQL backup.
3. Commit and push your code to GitHub.
4. On the target computer/server run `git pull origin main`.
5. If a database migration SQL file changed, import that migration into the `station` database.

For a real production server, use a private GitHub repository and never commit database passwords, uploaded patient files or SQL backups containing sensitive patient information.
