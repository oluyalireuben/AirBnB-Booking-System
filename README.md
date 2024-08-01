# AirBnB Booking System

## Overview
This project is a simplified AirBnB booking system developed using HTML, CSS, JavaScript, and PHP. The system allows users to register as either normal users or admin users. Normal users can book rooms, and admin users can manage bookings by approving or cancelling them. Additionally, the system includes OTP verification for user registration.

## Features
- **User Registration:** Users can register as normal or admin users.
- **Login:** Common login page for all users.
- **OTP Verification:** OTP sent to user's email during registration for verification.
- **Normal User Dashboard:**
  - Browse and book rooms.
  - View booking details.
  - Logout functionality.
- **Admin User Dashboard:**
  - View all bookings made by normal users.
  - Approve or cancel bookings.
  - Send feedback to users.
  - Logout functionality.
- **Forgot Password:** Users can reset their password if forgotten.

## Technologies Used
- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP
- **Database:** MySQL
- **Mailing:** PHPMailer

## Installation and Setup

### Prerequisites
- XAMPP (or any other local server with PHP and MySQL support)
- Composer (for managing PHP dependencies)

### Steps
1. **Clone the repository:**
2. Install dependencies using Composer:
    composer require phpmailer/phpmailer
   
4. Set up the database:
- Open phpMyAdmin or any MySQL database management tool.
- Create a new database named airbnb.
- Run the following SQL script to create the necessary tables:
  
```
   -- Create the database
CREATE DATABASE airbnb;

-- Use the database
USE airbnb;

-- Create the users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    user_type ENUM('normal', 'admin') NOT NULL
);

-- Create the users_temp table for OTP verification
CREATE TABLE users_temp (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    user_type ENUM('normal', 'admin') NOT NULL,
    otp INT NOT NULL
);

-- Create the bookings table
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    room VARCHAR(50) NOT NULL,
    days INT NOT NULL,
    status ENUM('pending', 'approved', 'cancelled') DEFAULT 'pending',
    FOREIGN KEY (username) REFERENCES users(username)
);

-- Insert sample users
INSERT INTO users (username, password, email, user_type) VALUES 
('normaluser1', '$2y$10$e9rY7lmNRnDzO4rIoNL2fOXg1D8mG/DFGtEp3iZLT8X7wIKfrwx6i', 'normal', 'normaluser1@example.com'),  -- password: normalpassword
('adminuser1', '$2y$10$e9rY7lmNRnDzO4rIoNL2fOXg1D8mG/DFGtEp3iZLT8X7wIKfrwx6i', 'admin', 'adminuser1@example.com');      -- password: adminpassword

-- Insert sample bookings
INSERT INTO bookings (username, room, days, status) VALUES 
('normaluser1', 'Room 1', 2, 'pending'),
('normaluser1', 'Room 2', 3, 'approved');
```

4. Configure email settings:
- Update the register.php file with your email credentials for PHPMailer:
```
   $mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'your-email@gmail.com';
$mail->Password = 'your-email-password';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;
$mail->setFrom('your-email@gmail.com', 'AirBnB Booking System');
```

5. Run the project:
- Start your local server (Apache and MySQL) using XAMPP.
- Open your browser and navigate to http://localhost/projects/AirBnB_BokingSystem/


## Usage
1. Register a new account:
- Navigate to the registration page.
- Fill in the required details and select user type.
- Complete OTP verification via email.

2. Login:

- Use the login page to access the normal user or admin dashboard based on your credentials.

3. Normal User Dashboard:
- Browse available rooms and make bookings.
- View booking details.
4. Admin User Dashboard:
- View and manage user bookings.
- Approve or cancel bookings with feedback sent to users.

  
