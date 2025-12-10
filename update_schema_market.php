<?php
include 'db_connect.php';

// disable foreign key checks to avoid issues during alteration if tables exist
$conn->query("SET foreign_key_checks = 0");

// 1. Update players table
$sql_players = "ALTER TABLE players 
ADD COLUMN market_status ENUM('Free Agent', 'For Sale', 'For Loan', 'Sold', 'Unavailable') DEFAULT 'Unavailable',
ADD COLUMN market_value DECIMAL(15, 2) DEFAULT 0.00,
ADD COLUMN owner_id INT DEFAULT NULL";

if ($conn->query($sql_players) === TRUE) {
    echo "Table 'players' updated successfully.<br>";
} else {
    // If column already exists it might fail, which is fine for idempotent runs, but let's show error
    echo "Error updating 'players' table: " . $conn->error . "<br>";
}

// 2. Create bids table
$sql_bids = "CREATE TABLE IF NOT EXISTS bids (
    id INT AUTO_INCREMENT PRIMARY KEY,
    player_id INT NOT NULL,
    bidder_id INT NOT NULL,
    amount DECIMAL(15, 2) NOT NULL,
    status ENUM('Pending', 'Accepted', 'Rejected') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (player_id) REFERENCES players(id) ON DELETE CASCADE,
    FOREIGN KEY (bidder_id) REFERENCES users(id) ON DELETE CASCADE
)";

if ($conn->query($sql_bids) === TRUE) {
    echo "Table 'bids' created successfully.<br>";
} else {
    echo "Error creating 'bids' table: " . $conn->error . "<br>";
}

$conn->query("SET foreign_key_checks = 1");
$conn->close();
?>