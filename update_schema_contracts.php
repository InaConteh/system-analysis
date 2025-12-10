<?php
include 'db_connect.php';

// disable foreign key checks
$conn->query("SET foreign_key_checks = 0");

// 1. Update players table using idempotent ALTER if possible, or just try
$sql_players = "ALTER TABLE players 
ADD COLUMN contract_start DATE DEFAULT NULL,
ADD COLUMN contract_end DATE DEFAULT NULL";

if ($conn->query($sql_players) === TRUE) {
    echo "Table 'players' updated with contract columns.<br>";
} else {
    echo "Note on 'players': " . $conn->error . "<br>";
}

// 2. Create player_videos table
$sql_videos = "CREATE TABLE IF NOT EXISTS player_videos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    player_id INT NOT NULL,
    video_url VARCHAR(255) NOT NULL,
    thumbnail_url VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (player_id) REFERENCES players(id) ON DELETE CASCADE
)";

if ($conn->query($sql_videos) === TRUE) {
    echo "Table 'player_videos' created successfully.<br>";
} else {
    echo "Error creating 'player_videos' table: " . $conn->error . "<br>";
}

$conn->query("SET foreign_key_checks = 1");
$conn->close();
?>