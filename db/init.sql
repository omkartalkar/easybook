-- Drop existing tables if they exist
DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS rooms;
DROP TABLE IF EXISTS users;

-- Create users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    department VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- Storing hashed password using MD5
    role ENUM('student', 'professor', 'admin') NOT NULL,
    status ENUM('approved', 'pending') NOT NULL DEFAULT 'pending'
);

-- Create rooms table
CREATE TABLE rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_image VARCHAR(255) NOT NULL,
    room_number VARCHAR(20) NOT NULL,
    capacity INT NOT NULL,
    floor_number INT NOT NULL,
    status ENUM('available', 'unavailable') NOT NULL DEFAULT 'available',
    has_smartboard BOOLEAN NOT NULL DEFAULT FALSE,
    has_projector BOOLEAN NOT NULL DEFAULT FALSE,
    has_ac BOOLEAN NOT NULL DEFAULT FALSE
);

-- Create bookings table
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    room_id INT NOT NULL,
    booking_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (room_id) REFERENCES rooms(id)
);

-- Insert sample data into users table with MD5 hashed passwords
INSERT INTO users (name, department, email, password, role, status) VALUES
('Admin User', 'Administration', 'admin@example.com', MD5('admin123'), 'admin', 'approved'),
('John Doe', 'Computer Science', 'johndoe@example.com', MD5('password'), 'student', 'approved'),
('Jane Smith', 'Mathematics', 'janesmith@example.com', MD5('password'), 'professor', 'approved'),
('Alice Brown', 'Physics', 'alicebrown@example.com', MD5('password'), 'student', 'approved'),
('Bob White', 'Chemistry', 'bobwhite@example.com', MD5('password'), 'professor', 'approved');

-- Insert sample data into rooms table
INSERT INTO rooms (room_image, room_number, capacity, floor_number, status, has_smartboard, has_projector, has_ac) VALUES
('room1.jpg', '101', 30, 1, 'available', TRUE, TRUE, TRUE),
('room2.jpg', '102', 25, 1, 'available', FALSE, TRUE, FALSE),
('room3.jpg', '201', 40, 2, 'available', TRUE, FALSE, TRUE),
('room4.jpg', '202', 20, 2, 'unavailable', FALSE, TRUE, TRUE),
('room5.jpg', '301', 35, 3, 'available', TRUE, TRUE, TRUE),
('room6.jpg', '302', 30, 3, 'available', FALSE, FALSE, FALSE),
('room7.jpg', '401', 50, 4, 'available', TRUE, TRUE, FALSE),
('room8.jpg', '402', 15, 4, 'unavailable', FALSE, FALSE, TRUE),
('room9.jpg', '403', 20, 4, 'available', TRUE, FALSE, TRUE),
('room10.jpg', '404', 30, 4, 'available', FALSE, TRUE, FALSE);
