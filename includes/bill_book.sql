

-- users table
CREATE TABLE IF NOT EXISTS users (
    `user_id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(255) NOT NULL UNIQUE,
    `email` VARCHAR(255) NOT NULL,
    `role` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL
);
ALTER TABLE users ADD COLUMN role ENUM('Admin', 'Manager', 'Clerk', 'Customer') NOT NULL;


-- products table
CREATE TABLE IF NOT EXISTS products (
    `product_id` INT AUTO_INCREMENT PRIMARY KEY,
    `product_name` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `price` DECIMAL(10, 2) NOT NULL
);


-- Customers table
CREATE TABLE IF NOT EXISTS Customers (
    `CustomerID` INT AUTO_INCREMENT PRIMARY KEY,
    `FirstName` VARCHAR(50) NOT NULL,
    LastName VARCHAR(50) NOT NULL,
    `Email` VARCHAR(100) NOT NULL,
    `Phone` VARCHAR(20) NOT NULL,
    `Address` VARCHAR(255) NOT NULL
);


-- invoices table
CREATE TABLE IF NOT EXISTS invoices (
    `invoice_id` INT AUTO_INCREMENT PRIMARY KEY,
    `customer_id` INT NOT NULL,
    `invoice_date` DATE NOT NULL,
    `total_amount` DECIMAL(10, 2) NOT NULL,
    Status ENUM('Pending', 'Paid', 'Cancelled') NOT NULL DEFAULT 'Pending'
);


-- Payments table
CREATE TABLE IF NOT EXISTS Payments (
    `PaymentID` INT AUTO_INCREMENT PRIMARY KEY,
    `InvoiceID` INT NOT NULL,
    `PaymentDate` DATE NOT NULL,
    `Amount` DECIMAL(10, 2) NOT NULL,
    `PaymentMethod` VARCHAR(50) NOT NULL
);