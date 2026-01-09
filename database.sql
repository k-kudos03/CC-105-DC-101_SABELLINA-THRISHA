CREATE DATABASE IF NOT EXISTS GymFlow;
USE GymFlow;

-- 1. Plans Table
CREATE TABLE IF NOT EXISTS plans (
    plan_id INT PRIMARY KEY AUTO_INCREMENT,
    plan_name VARCHAR(50) NOT NULL,
    monthly_fee DECIMAL(10, 2) NOT NULL
);

-- 2. Members Table
CREATE TABLE IF NOT EXISTS members (
    member_id INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    plan_id INT,
    FOREIGN KEY (plan_id) REFERENCES plans(plan_id) ON DELETE SET NULL
);

-- 3. Attendance Table
CREATE TABLE IF NOT EXISTS attendance (
    log_id INT PRIMARY KEY AUTO_INCREMENT,
    member_id INT NOT NULL,
    check_in_date DATE DEFAULT (CURRENT_DATE),
    UNIQUE KEY unique_daily_visit (member_id, check_in_date),
    FOREIGN KEY (member_id) REFERENCES members(member_id) ON DELETE CASCADE
);

-- 4. Initial Data (Philippine Peso Prices)
INSERT INTO plans (plan_name, monthly_fee) VALUES 
('Student Pass', 800.00), 
('Regular Monthly', 1500.00), 
('Elite VIP', 3500.00);