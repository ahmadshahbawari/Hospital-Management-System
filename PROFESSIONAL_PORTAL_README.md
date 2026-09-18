# Station Hospital — Professional Single Portal

This version changes the system entry point to **`index.php`**.

## What changed

- One professional portal for **Administrator, Doctor, and Patient/User**.
- Role cards on one login screen.
- One dashboard after login with a left sidebar.
- Modules open inside the dashboard, so the user does not need to type separate PHP page URLs.
- Responsive mobile sidebar.
- Existing hospital modules are reused inside the workspace.
- Successful legacy plaintext passwords are automatically upgraded to PHP `password_hash()` format for the portal login.
- Existing database schema is preserved.

## XAMPP setup

1. Extract this folder into:
   `C:\xampp\htdocs\`
2. Start **Apache** and **MySQL** in XAMPP.
3. Open phpMyAdmin and create/import the database from:
   `database/station.sql`
4. Make sure `dbconnection.php` matches your MySQL credentials.
5. Open the system once through the project `index.php`.

After opening the portal, users should use the **role buttons** rather than typing individual files such as `adminaccount.php`, `doctor.php`, etc.

## Existing sample accounts from the supplied database

Administrator:
- Login ID: `admin`
- Password: `adminadmin`

Doctor:
- Login ID: `doctoramina`
- Password: `123456789`

Patient/User:
- The supplied database does not contain an active patient account. Create a patient from the existing patient-management module first.

## Important

The old public hospital homepage has been preserved as `home.php`.

The original PHP modules are still present because the portal uses them as the system's internal modules. They are not removed; the dashboard is now the normal entry point.
