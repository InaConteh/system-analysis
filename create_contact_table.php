<?php
// Create contact_submissions table
require_once 'db_connect.php';

$sql = "CREATE TABLE IF NOT EXISTS contact_submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    message TEXT NOT NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('new', 'read') DEFAULT 'new',
    INDEX idx_status (status),
    INDEX idx_submitted_at (submitted_at)
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'contact_submissions' created successfully or already exists.";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>
