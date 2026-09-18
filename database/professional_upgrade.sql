-- Professional HMS upgrade. Import after station.sql.
SET NAMES utf8mb4;

ALTER TABLE doctor ADD COLUMN IF NOT EXISTS salary DECIMAL(12,2) NOT NULL DEFAULT 0 AFTER consultancy_charge;
ALTER TABLE doctor ADD COLUMN IF NOT EXISTS profile_image VARCHAR(255) NOT NULL DEFAULT '' AFTER salary;
ALTER TABLE patient ADD COLUMN IF NOT EXISTS profile_image VARCHAR(255) NOT NULL DEFAULT '' AFTER status;
ALTER TABLE patient MODIFY password VARCHAR(255) NOT NULL;
ALTER TABLE admin MODIFY password VARCHAR(255) NOT NULL;
ALTER TABLE doctor MODIFY password VARCHAR(255) NOT NULL;
ALTER TABLE `user` MODIFY password VARCHAR(255) NOT NULL;

CREATE TABLE IF NOT EXISTS salary_payments (
  salary_payment_id INT AUTO_INCREMENT PRIMARY KEY,
  doctorid INT NOT NULL,
  payment_date DATE NOT NULL,
  amount DECIMAL(12,2) NOT NULL,
  note VARCHAR(255) NOT NULL DEFAULT '',
  status VARCHAR(20) NOT NULL DEFAULT 'Paid',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX (doctorid), INDEX (payment_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS hospital_settings (
  setting_id INT AUTO_INCREMENT PRIMARY KEY,
  hospital_name VARCHAR(150) NOT NULL DEFAULT 'Station Hospital',
  hospital_logo VARCHAR(255) NOT NULL DEFAULT '',
  currency VARCHAR(10) NOT NULL DEFAULT 'AFN',
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO hospital_settings (setting_id) VALUES (1) ON DUPLICATE KEY UPDATE setting_id=setting_id;

CREATE TABLE IF NOT EXISTS patient_accounts (
  account_id INT AUTO_INCREMENT PRIMARY KEY,
  patientid INT NOT NULL UNIQUE,
  username VARCHAR(100) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  last_login DATETIME NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX(patientid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS financial_transactions (
  transaction_id INT AUTO_INCREMENT PRIMARY KEY,
  transaction_type ENUM('patient_fee','doctor_salary','other_income','other_expense') NOT NULL,
  patientid INT NULL,
  doctorid INT NULL,
  appointmentid INT NULL,
  amount DECIMAL(12,2) NOT NULL,
  transaction_date DATE NOT NULL,
  description VARCHAR(255) NOT NULL DEFAULT '',
  status VARCHAR(20) NOT NULL DEFAULT 'Paid',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX(transaction_type), INDEX(transaction_date), INDEX(patientid), INDEX(doctorid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Existing patient records become accounts automatically when the application logs them in.
ALTER TABLE admin ADD COLUMN IF NOT EXISTS profile_image VARCHAR(255) NOT NULL DEFAULT '' AFTER usertype;

-- Demo patient for testing the new patient portal (remove/change in production).
INSERT INTO patient (patientname, admissiondate, admissiontime, address, mobileno, city, pincode, loginid, password, bloodgroup, gender, dob, status)
SELECT 'Demo Patient', CURDATE(), CURTIME(), '', '0700000000', 'Kabul', '', 'patient1', '$2y$12$nBS1ORk1ayDJ7tQ3diBRUuq3nLWuZHXB1JbXYM9p3P0HBjq47NuES', 'O+', 'Male', '2000-01-01', 'Active'
WHERE NOT EXISTS (SELECT 1 FROM patient WHERE loginid='patient1');
