-- Professional HMS v3 upgrade. Run AFTER station.sql and professional_upgrade.sql.
SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS workers (
 worker_id INT AUTO_INCREMENT PRIMARY KEY,
 worker_name VARCHAR(150) NOT NULL,
 job_title VARCHAR(100) NOT NULL DEFAULT 'Staff',
 loginid VARCHAR(100) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL,
 phone VARCHAR(50) NOT NULL DEFAULT '',
 salary DECIMAL(12,2) NOT NULL DEFAULT 0,
 profile_image VARCHAR(255) NOT NULL DEFAULT '',
 status VARCHAR(20) NOT NULL DEFAULT 'Active',
 created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 INDEX(status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS attendance (
 attendance_id INT AUTO_INCREMENT PRIMARY KEY,
 role_type ENUM('doctor','worker') NOT NULL,
 doctorid INT NULL,
 worker_id INT NULL,
 attendance_date DATE NOT NULL,
 check_in TIME NULL,
 check_out TIME NULL,
 status ENUM('Present','Absent','Late','Leave') NOT NULL DEFAULT 'Present',
 marked_by VARCHAR(100) NOT NULL DEFAULT 'Self',
 note VARCHAR(255) NOT NULL DEFAULT '',
 created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 UNIQUE KEY uniq_attendance (role_type,doctorid,worker_id,attendance_date),
 INDEX(attendance_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS system_settings (
 setting_key VARCHAR(100) PRIMARY KEY,
 setting_value TEXT NOT NULL,
 updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO system_settings(setting_key,setting_value) VALUES
('default_language','en'),('timezone','Asia/Kabul'),('date_format','Y-m-d'),('time_format','H:i'),('currency','AFN'),('patient_fee_default','0'),('doctor_salary_cycle','Monthly'),('auto_backup','Manual'),('backup_folder','backups'),('github_repository',''),('github_branch','main'),('hospital_email',''),('hospital_phone',''),('hospital_address',''),('appointment_duration','30'),('low_stock_threshold','10'),('session_timeout','60'),('maintenance_mode','0')
ON DUPLICATE KEY UPDATE setting_key=setting_key;

CREATE TABLE IF NOT EXISTS audit_logs (
 log_id BIGINT AUTO_INCREMENT PRIMARY KEY,
 loginid VARCHAR(100) NOT NULL DEFAULT '',
 role_type VARCHAR(30) NOT NULL DEFAULT '',
 action VARCHAR(100) NOT NULL,
 details TEXT NOT NULL,
 created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 INDEX(created_at), INDEX(loginid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS backup_history (
 backup_id INT AUTO_INCREMENT PRIMARY KEY,
 filename VARCHAR(255) NOT NULL,
 created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 created_by VARCHAR(100) NOT NULL DEFAULT '',
 backup_type VARCHAR(30) NOT NULL DEFAULT 'SQL'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS github_settings (
 id INT PRIMARY KEY,
 repository_url VARCHAR(500) NOT NULL DEFAULT '',
 branch_name VARCHAR(100) NOT NULL DEFAULT 'main',
 deploy_method VARCHAR(50) NOT NULL DEFAULT 'Git Pull',
 notes TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO github_settings(id,repository_url,branch_name,notes) VALUES(1,'','main','Configure Git on the server/PC, then use git pull to update code. Database backups remain separate.')
ON DUPLICATE KEY UPDATE id=id;

ALTER TABLE doctor ADD COLUMN IF NOT EXISTS profile_image VARCHAR(255) NOT NULL DEFAULT '';
ALTER TABLE doctor ADD COLUMN IF NOT EXISTS salary DECIMAL(12,2) NOT NULL DEFAULT 0;
ALTER TABLE patient ADD COLUMN IF NOT EXISTS profile_image VARCHAR(255) NOT NULL DEFAULT '';
ALTER TABLE admin ADD COLUMN IF NOT EXISTS profile_image VARCHAR(255) NOT NULL DEFAULT '';

INSERT INTO workers(worker_name,job_title,loginid,password,phone,salary,status)
SELECT 'Demo Worker','Receptionist','worker1','$2y$12$cxs4R65WQ9K4fCNeiQRgMuT7FuHBAC5V/JktfRvX6QIzFgMFsiEzW','0700000001',15000,'Active'
WHERE NOT EXISTS (SELECT 1 FROM workers WHERE loginid='worker1');

-- Pharmacy management extension
CREATE TABLE IF NOT EXISTS pharmacy_medicines (
 id INT AUTO_INCREMENT PRIMARY KEY, medicine_name VARCHAR(120) NOT NULL, generic_name VARCHAR(120) DEFAULT '', category VARCHAR(80) DEFAULT '',
 dosage_form VARCHAR(60) DEFAULT '', strength VARCHAR(60) DEFAULT '', unit VARCHAR(30) DEFAULT 'unit', batch_no VARCHAR(80) DEFAULT '', expiry_date DATE NULL,
 purchase_price DECIMAL(12,2) DEFAULT 0, selling_price DECIMAL(12,2) DEFAULT 0, quantity INT DEFAULT 0, reorder_level INT DEFAULT 0,
 supplier VARCHAR(150) DEFAULT '', status VARCHAR(30) DEFAULT 'Active', created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, INDEX(expiry_date), INDEX(quantity)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS pharmacy_transactions (
 id INT AUTO_INCREMENT PRIMARY KEY, medicine_id INT NOT NULL, transaction_type ENUM('incoming','outgoing','adjustment','return') NOT NULL,
 quantity INT NOT NULL, unit_price DECIMAL(12,2) DEFAULT 0, patient_id INT DEFAULT 0, supplier VARCHAR(150) DEFAULT '', reference_no VARCHAR(100) DEFAULT '', note TEXT,
 transaction_date DATETIME DEFAULT CURRENT_TIMESTAMP, created_by VARCHAR(100) DEFAULT '', INDEX(medicine_id), INDEX(transaction_type), INDEX(transaction_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
