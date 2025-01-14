CREATE DATABASE repair_management_system;
USE repair_management_system;

-- Users Table
CREATE TABLE Users (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    FirstName VARCHAR(50) NOT NULL,
    LastName VARCHAR(50) NOT NULL,
    Username VARCHAR(50) UNIQUE NOT NULL,
    Password VARCHAR(255) NOT NULL,
    Phone VARCHAR(15),
    Email VARCHAR(100) UNIQUE NOT NULL,
    ProfilePicture VARCHAR(255),
    UserType ENUM('Admin', 'User') DEFAULT 'User',
    Status ENUM('Active', 'Inactive') DEFAULT 'Active'
);

-- Repairs Table
CREATE TABLE Repairs (
    RepairID INT AUTO_INCREMENT PRIMARY KEY,
    DeviceType VARCHAR(100) NOT NULL,
    IssueDescription TEXT NOT NULL,
    CustomerName VARCHAR(100) NOT NULL,
    ContactDetails VARCHAR(100) NOT NULL,
    Status ENUM('Pending', 'In Progress', 'Completed') DEFAULT 'Pending',
    AssignedTechnician VARCHAR(100)
);

-- Logs Table
CREATE TABLE Logs (
    LogID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT,
    ActionPerformed VARCHAR(255),
    Timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);