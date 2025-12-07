<?php
include 'db_connect.php';

$sql = "ALTER TABLE players ADD COLUMN nationality VARCHAR(100) NOT NULL DEFAULT 'Unknown'";

if ($conn->query($sql) === TRUE) {
    echo "Table players updated successfully with 'nationality' column.";
} else {
    echo "Error updating table: " . $conn->error;
}

$conn->close();
?>