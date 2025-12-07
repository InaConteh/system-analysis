<?php
include 'db_connect.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $conn->prepare("SELECT * FROM players WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $player = $result->fetch_assoc();

    if ($player) {
        $filename = "player_report_" . str_replace(' ', '_', $player['name']) . ".txt";
        $content = "FOOTBALL AGENCY - PLAYER REPORT\n";
        $content .= "================================\n\n";
        $content .= "Name: " . $player['name'] . "\n";
        $content .= "Club: " . $player['club'] . "\n";
        $content .= "Age: " . $player['age'] . "\n";
        $content .= "Nationality: " . ($player['nationality'] ?? 'Unknown') . "\n";
        $content .= "Image: " . $player['image_url'] . "\n";
        $content .= "\nReport Generated: " . date("Y-m-d H:i:s") . "\n";

        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . strlen($content));
        echo $content;
        exit();
    }
}
echo "Player not found.";
?>