# Hospital Management System — Professional v4

## Main fixes

- Removed the generic **Clinical Data** sidebar group.
- Reorganized the sidebar into role-specific dropdowns: Patients, Doctors, Departments, Appointments, Staff, Finance and Settings for administrators; Doctor, Worker and Patient menus for their respective roles.
- Patient menu now contains Patient Home, Profile, Appointments, Doctors, Orders, Health Record and Prescriptions.
- Doctor menu now contains Doctor Home, Profile, Appointments, Patients, Prescriptions, Schedule and Attendance.
- Login is now one professional card: Username → Password → Account Role → Sign In.
- Added reliable RTL positioning: Pashto and Dari put the sidebar on the right; English puts it on the left.
- Dark/light mode remains available and is remembered offline.
- Added an **Download Excel** button to the workspace header for modules with database records.
- Excel exports are generated as `.xlsx` when PHP ZipArchive is available, with the hospital logo embedded when a valid PNG/JPG logo is configured. A legacy `.xls` fallback is provided if ZipArchive is unavailable.
- Removed the old table-by-table Excel export list from System Tools. Full database SQL backup remains in the Backup module.
- Added `orders.php` so the Patient dropdown has a working Orders page.
- Doctor delete action remains available to administrators and now asks for confirmation.
- Existing modern dashboard line/bar financial charts are retained.

## Excel workflow

Open a module such as Patients, Doctors, Departments, Appointments, Finance, Attendance or Workers. Click **Download Excel** at the top of the module. The export uses the selected database table and includes the configured hospital logo.

## Database

No new database is required for these UI/export changes. Continue using the existing `station` database and the SQL migrations already supplied with v3.
