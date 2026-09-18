# Professional UI Upgrade

This build keeps the existing `station` database and core PHP application while adding a modern presentation layer.

## Added
- Responsive professional design system in `professional_assets/modern.css`
- Mobile navigation support in `professional_assets/modern.js`
- Modern header/footer components
- Professional doctor directory: `doctors.php`
- Professional contact page: `contact.php`
- Services page: `services.php`
- Search/filter on doctor directory
- Better appointment/contact calls-to-action
- Active navigation styling
- Responsive cards, forms, tables, dashboards and KPIs
- Quick actions on the homepage
- Legacy PHP/database content preserved

## Run
1. Put the project under `xampp/htdocs/`.
2. Start Apache and MySQL.
3. Import the existing `database/station.sql` into the `station` database.
4. Confirm `dbconnection.php` matches your MySQL credentials.
5. Open `index.php`.

## Demo contacts
The call/WhatsApp/email buttons use placeholders (`+0000000000`, `info@example.com`) because the original project does not provide verified hospital contact details. Replace these values before publishing.

## Important
This is a professional UI/UX upgrade, not a production-security certification. The legacy database and many original PHP pages still use older authentication and SQL patterns. Do not use real patient or payment-card data until those areas are securely redesigned.
