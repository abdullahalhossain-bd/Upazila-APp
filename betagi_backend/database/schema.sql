-- =====================================================
-- বেতাগী ই-সেবা — MySQL Schema
-- Import this into: nagorik1_betagi  (your cPanel DB)
-- =====================================================

USE nagorik1_betagi;

CREATE TABLE IF NOT EXISTS unions (
    id   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO unions (id, name) VALUES
(1,'বেতাগী সদর ইউনিয়ন পরিষদ'),(2,'বিবিচিনি ইউনিয়ন পরিষদ'),
(3,'মোকামিয়া ইউনিয়ন পরিষদ'),(4,'হোসনাবাদ ইউনিয়ন পরিষদ'),
(5,'বুড়িরচর ইউনিয়ন পরিষদ'),(6,'কাজিরাবাদ ইউনিয়ন পরিষদ'),
(7,'সরিষামুড়ি ইউনিয়ন পরিষদ');

CREATE TABLE IF NOT EXISTS users (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    phone      VARCHAR(20)  NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    address    TEXT,
    union_name VARCHAR(150),
    image      VARCHAR(255) DEFAULT 'default.png',
    user_type  ENUM('user','admin') DEFAULT 'user',
    is_active  TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS notices (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(255) NOT NULL,
    type        VARCHAR(50)  DEFAULT 'সাধারণ',
    department  VARCHAR(150),
    description TEXT         NOT NULL,
    image       VARCHAR(255),
    notice_date DATE,
    created_by  INT UNSIGNED,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS notice_attachments (
    id        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    notice_id INT UNSIGNED NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_type VARCHAR(20)  DEFAULT 'pdf',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (notice_id) REFERENCES notices(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS blood_donors (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100) NOT NULL,
    blood_group VARCHAR(5)   NOT NULL,
    address     TEXT,
    phone       VARCHAR(20),
    image       VARCHAR(255) DEFAULT 'default.png',
    user_id     INT UNSIGNED,
    is_active   TINYINT(1) DEFAULT 1,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS clinics (
    id                     INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name                   VARCHAR(200) NOT NULL,
    address                TEXT,
    place_name             VARCHAR(150),
    phone_number           VARCHAR(20),
    complaint_phone_number VARCHAR(20),
    email                  VARCHAR(150),
    establish_date         VARCHAR(30),
    founder_name           VARCHAR(100),
    transport_info         TEXT,
    operating_hours        VARCHAR(100),
    union_name             VARCHAR(150),
    img                    VARCHAR(255) DEFAULT 'default.png',
    services               JSON,
    created_by             INT UNSIGNED,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS specialist_doctors (
    id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name           VARCHAR(100) NOT NULL,
    qualifications TEXT,
    specialization VARCHAR(150),
    doctor_type    VARCHAR(100),
    workplace      VARCHAR(200),
    chamber        VARCHAR(200),
    visiting_hours VARCHAR(150),
    phone          VARCHAR(20),
    address        TEXT,
    union_name     VARCHAR(150),
    image          VARCHAR(255) DEFAULT 'default.png',
    user_id        INT UNSIGNED,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS unified_govt_items (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name         VARCHAR(200) NOT NULL,
    address      TEXT,
    phone_number VARCHAR(20),
    item_type    VARCHAR(50),
    img_url      VARCHAR(255) DEFAULT 'default.png',
    union_name   VARCHAR(150),
    created_by   INT UNSIGNED,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS unified_govt_officers (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name         VARCHAR(100) NOT NULL,
    designation  VARCHAR(150),
    phone_number VARCHAR(20),
    officer_type VARCHAR(100),
    image        VARCHAR(255) DEFAULT 'default.png',
    user_id      INT UNSIGNED,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS unified_business_items (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id         INT UNSIGNED,
    name            VARCHAR(200) NOT NULL,
    proprietor_name TEXT,
    phone           VARCHAR(20),
    address         TEXT,
    union_name      VARCHAR(150),
    business_type   VARCHAR(100),
    details         TEXT,
    image           VARCHAR(255) DEFAULT 'default.png',
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS unified_persons (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100) NOT NULL,
    phone       VARCHAR(20),
    address     TEXT,
    union_name  VARCHAR(150),
    image_url   VARCHAR(255) DEFAULT 'default.png',
    person_type VARCHAR(100),
    user_id     INT UNSIGNED,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS complaints (
    id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id          INT UNSIGNED NOT NULL,
    type             VARCHAR(100),
    title            VARCHAR(255),
    details          TEXT,
    location         VARCHAR(255),
    department       VARCHAR(150),
    priority         VARCHAR(20) DEFAULT 'medium',
    contact_number   VARCHAR(20),
    email            VARCHAR(150),
    complainant_name VARCHAR(100),
    complaint_date   DATE,
    image_url        VARCHAR(255),
    status           ENUM('pending','in_progress','resolved','rejected') DEFAULT 'pending',
    admin_note       TEXT,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS budget_categories (
    id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fiscal_year      VARCHAR(20)   DEFAULT '2024-2025',
    category_name    VARCHAR(200)  NOT NULL,
    allocated_amount DECIMAL(15,2) DEFAULT 0,
    spent_amount     DECIMAL(15,2) DEFAULT 0,
    created_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO budget_categories (fiscal_year, category_name, allocated_amount, spent_amount) VALUES
('2024-2025','শিক্ষা ও প্রশিক্ষণ',5000000,2500000),
('2024-2025','স্বাস্থ্য ও পরিবার পরিকল্পনা',3000000,1200000),
('2024-2025','কৃষি ও সেচ',2000000,900000),
('2024-2025','অবকাঠামো উন্নয়ন',8000000,4000000),
('2024-2025','সামাজিক সুরক্ষা',1500000,700000);

CREATE TABLE IF NOT EXISTS market_rates (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    item_name  VARCHAR(100)  NOT NULL,
    price      DECIMAL(10,2) NOT NULL,
    unit       VARCHAR(30)   DEFAULT 'কেজি',
    rate_date  DATE          NOT NULL,
    created_by INT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS emergency_numbers (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(150) NOT NULL,
    phone      VARCHAR(30)  NOT NULL,
    type       VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO emergency_numbers (name, phone, type) VALUES
('বেতাগী থানা','01769-690062','পুলিশ'),
('ফায়ার সার্ভিস','16163','ফায়ার সার্ভিস'),
('উপজেলা স্বাস্থ্য কমপ্লেক্স','0445456080','হাসপাতাল'),
('অ্যাম্বুলেন্স','01711-000999','অ্যাম্বুলেন্স'),
('জাতীয় জরুরি সেবা','999','জাতীয়');

-- =====================================================
-- Phase 6 — Admin API layer schema additions
-- (Append-only. Existing tables are NOT dropped/recreated.)
-- =====================================================

-- 1. Extend users.user_type ENUM to include the 3 admin roles:
--    uno       → উপজেলা নির্বাহী অফিসার  (UNO — full access)
--    ict       → উপজেলা আইসিটি অফিসার     (ICT Officer — content)
--    developer → অ্যাপ ডেভেলপার          (App Developer — technical/settings)
-- 'admin' remains as a legacy superuser. Safe to re-run on existing DB.
ALTER TABLE users
    MODIFY COLUMN user_type ENUM('user','admin','uno','ict','developer') DEFAULT 'user';

-- 2. admin_roles — Bangla display name + description per role_key
CREATE TABLE IF NOT EXISTS admin_roles (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_key    VARCHAR(20)  NOT NULL UNIQUE,   -- 'uno', 'ict', 'developer'
    role_name   VARCHAR(100) NOT NULL,          -- 'উপজেলা নির্বাহী অফিসার'
    description TEXT,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO admin_roles (role_key, role_name, description) VALUES
('uno','উপজেলা নির্বাহী অফিসার','Full access to all admin features'),
('ict','উপজেলা আইসিটি অফিসার','Content management access'),
('developer','অ্যাপ ডেভেলপার','Technical/settings access');

-- 3. complaint_replies — admin responses + status-change history per complaint
CREATE TABLE IF NOT EXISTS complaint_replies (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    complaint_id INT UNSIGNED NOT NULL,
    admin_id     INT UNSIGNED NOT NULL,
    reply_text   TEXT NOT NULL,
    new_status   VARCHAR(20) DEFAULT 'in_progress',
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (complaint_id) REFERENCES complaints(id) ON DELETE CASCADE,
    FOREIGN KEY (admin_id)     REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. notifications — broadcast notifications shown in NotifiationActivity
CREATE TABLE IF NOT EXISTS notifications (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title      VARCHAR(255) NOT NULL,
    body       TEXT,
    type       VARCHAR(50)  DEFAULT 'general',
    sent_by    INT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sent_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. admin_audit_log — best-effort audit trail of all admin write actions
CREATE TABLE IF NOT EXISTS admin_audit_log (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    admin_id    INT UNSIGNED NOT NULL,
    action      VARCHAR(50)  NOT NULL,        -- 'create','update','delete','login', etc.
    entity_type VARCHAR(50)  NOT NULL,        -- 'notice','donor','clinic','complaint', etc.
    entity_id   INT UNSIGNED,
    details     TEXT,
    ip_address  VARCHAR(45),
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- Rate limiting table (for rateLimitCheck helper)
-- =====================================================
CREATE TABLE IF NOT EXISTS rate_limit (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    identifier  VARCHAR(255) NOT NULL,
    action      VARCHAR(100) NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_rate_identifier (identifier, action),
    INDEX idx_rate_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
