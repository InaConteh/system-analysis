<?php
session_start();
include 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$is_admin = ($_SESSION['role'] === 'admin');

// Handle Delete Request (Admin only)
if ($is_admin && isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $conn->query("DELETE FROM players WHERE id=$delete_id");
    header("Location: players.php");
    exit();
}

// Fetch Players with Filter
$filter_status = isset($_GET['status']) ? $_GET['status'] : '';
$sql = "SELECT * FROM players";
if ($filter_status) {
    $sql .= " WHERE market_status = '$filter_status'";
}
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Players Directory | Football Agency</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">

</head>

<body>
    <header>
        <nav class="navbar">
            <a href="index.php" class="logo">
                <img src="images/logo.png" alt="LionSport Agency">
            </a>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li class="active-link"><a href="players.php">Players</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
            </ul>
        </nav>
    </header>

    <main class="players-directory-container">
        <h2 class="animate-on-scroll">Players Directory</h2>

        <form method="GET" action="players.php" style="margin-bottom: 20px;">
            <label for="status">Filter by Status:</label>
            <select name="status" id="status" onchange="this.form.submit()">
                <option value="">All</option>
                <option value="Free Agent" <?php if ($filter_status == 'Free Agent')
                    echo 'selected'; ?>>Free Agent
                </option>
                <option value="For Sale" <?php if ($filter_status == 'For Sale')
                    echo 'selected'; ?>>For Sale</option>
                <option value="For Loan" <?php if ($filter_status == 'For Loan')
                    echo 'selected'; ?>>For Loan</option>
                <option value="Sold" <?php if ($filter_status == 'Sold')
                    echo 'selected'; ?>>Sold</option>
                <option value="Unavailable" <?php if ($filter_status == 'Unavailable')
                    echo 'selected'; ?>>Unavailable
                </option>
            </select>
        </form>

        <?php if ($is_admin): ?>
            <div style="margin: 20px 0;">
                <a href="add_player.php" class="cta-button">Add New Player</a>
            </div>
        <?php endif; ?>

        <section class="player-grid">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="player-directory-card animate-on-scroll animate-scale">';
                    // Helper to link to contract or details page if needed
                    echo '<a href="contract.php?id=' . $row['id'] . '" class="player-card-link">';
                    echo '<img src="' . htmlspecialchars($row["image_url"]) . '" alt="' . htmlspecialchars($row["name"]) . '">';
                    echo '<h3>' . htmlspecialchars($row["name"]) . '</h3>';
                    echo '<p>' . htmlspecialchars($row["club"]) . '</p>';
                    echo '<p>Age: ' . htmlspecialchars($row["age"]) . '</p>';
                    echo '<p>Status: <strong>' . htmlspecialchars($row["market_status"]) . '</strong></p>';
                    if ($row["market_status"] == 'For Sale' || $row["market_status"] == 'For Loan') {
                        echo '<p>Value: $' . number_format($row["market_value"]) . '</p>';
                    }
                    echo '</a>';

                    if ($is_admin) {
                        echo '<div class="admin-controls">';
                        // Placeholder for edit functionality
                        echo '<a href="edit_player.php?id=' . $row['id'] . '" class="btn-edit">Edit</a>';
                        echo '<a href="players.php?delete_id=' . $row['id'] . '" class="btn-delete" onclick="return confirm(\'Are you sure?\')">Delete</a>';
                        echo '</div>';
                    }
                    echo '</div>';
                }
            } else {
                echo "<p>No players found.</p>";
            }
            ?>
        </section>
    </main>

    <?php include 'footer.php'; ?>
    <script src="main.js"></script>
</body>

</html>
<?php $conn->close(); ?>