CREATE DATABASE IF NOT EXISTS football_agency_db;
USE football_agency_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS players (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    club VARCHAR(100) NOT NULL,
    age INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert dummy data for players
INSERT INTO players (name, club, age, image_url) VALUES 
('Kei Kamara', 'LA GALAXY fc', 36, 'player-1.jpg'),
('Mohamed Kamara', 'Horoya', 26, 'player-2.jpg');

-- Insert a default admin user (password: admin123)
-- Hash: $2y$10$8.w... (You should generate a real hash, but for now we will rely on registration to create users)
-- For testing, we can register a user 'admin' via the form and then manually update their role to 'admin' in the database.
