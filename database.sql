CREATE DATABASE IF NOT EXISTS courierms;
USE courierms;

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE agents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    city VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    address VARCHAR(255),
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE couriers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    consignment_no VARCHAR(50) NOT NULL UNIQUE,
    sender_name VARCHAR(100) NOT NULL,
    sender_phone VARCHAR(20),
    receiver_name VARCHAR(100) NOT NULL,
    receiver_phone VARCHAR(20),
    from_location VARCHAR(100) NOT NULL,
    to_location VARCHAR(100) NOT NULL,
    courier_type VARCHAR(50) DEFAULT 'Standard',
    status ENUM('Booked', 'In Transit', 'Out for Delivery', 'Delivered') DEFAULT 'Booked',
    booked_by INT,
    delivery_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO admins (name, email, password) VALUES
('Admin User', 'admin@courierms.com', '$2y$10$vWpF48AN1A07yVzI/ODS5ec9q7RFUEIWmbMKzfU5nSgSTyI/R58IC');

INSERT INTO agents (name, email, password, city) VALUES
('Agent User', 'agent@courierms.com', '$2y$10$vWpF48AN1A07yVzI/ODS5ec9q7RFUEIWmbMKzfU5nSgSTyI/R58IC', 'Karachi');

INSERT INTO couriers (consignment_no, sender_name, receiver_name, from_location, to_location, status, delivery_date) VALUES
('CMS-2026-00452', 'Ali Raza', 'Sara Khan', 'Karachi', 'Lahore', 'In Transit', '2026-07-20'),
('CMS-2026-00453', 'Bilal Ahmed', 'Hina Malik', 'Islamabad', 'Karachi', 'Delivered', '2026-07-10');
