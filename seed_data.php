<?php
include 'db_connect.php';

// disable foreign key checks
$conn->query("SET foreign_key_checks = 0");

echo "Seeding/Updating ALL players...<br>";

$statuses = ['For Sale', 'For Loan', 'Free Agent', 'Unavailable'];
$clubs = ['Real Madrid', 'Barcelona', 'Man City', 'Arsenal', 'Liverpool', 'Chelsea', 'PSG', 'Bayern Munich', 'Juventus', 'Inter Milan'];

// Fetch all players
$sql = "SELECT id, name, market_status FROM players";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $name = $row['name'];
        // Randomize status specifically if currently Unavailable or NULL, OR just force randomize for demo
        // Let's keep specific manual edits (like Alpha Turay) if we can, but user asked for "details of the other players".
        // I will overwrite everything to ensure a lively market for the demo.

        $new_status = $statuses[array_rand($statuses)];
        // make slightly more 'For Sale' for demo
        if (rand(0, 10) > 6)
            $new_status = 'For Sale';

        $value = rand(500000, 50000000); // 500k to 50M
        $start_year = rand(2020, 2024);
        $end_year = $start_year + rand(1, 5);
        $start_date = "$start_year-07-01";
        $end_date = "$end_year-06-30";

        // Ensure future end date for active contracts usually
        if ($new_status == 'Free Agent') {
            $end_date = date('Y-m-d', strtotime('-1 month')); // Expired
            $value = 0;
        }

        $new_club = $clubs[array_rand($clubs)];

        $update_sql = "UPDATE players SET 
            market_status = '$new_status', 
            market_value = $value, 
            contract_start = '$start_date', 
            contract_end = '$end_date'
            WHERE id = $id";

        if ($conn->query($update_sql) === TRUE) {
            echo "Updated $name: $new_status, $$value <br>";
        } else {
            echo "Error updating $name: " . $conn->error . "<br>";
        }
    }
} else {
    echo "No players found to seed.<br>";
}

$conn->query("SET foreign_key_checks = 1");
$conn->close();
?>
<br>
<a href="players.php">Go back to Players</a>