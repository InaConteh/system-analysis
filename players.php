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

// Fetch Players
$sql = "SELECT * FROM players";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Players Directory | Football Agency</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-controls {
            margin-top: 10px;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 0.9em;
        }

        .btn-edit {
            background-color: #ffc107;
            color: black;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 0.9em;
            margin-right: 5px;
        }

        .player-directory-card {
            display: block;
            /* Ensure it behaves like a block for the link */
            color: inherit;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="logo">Football Agency</div>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li class="active-link"><a href="players.php">Players</a></li>
                <li><a href="contract.php">Contract</a></li>
                <li><a href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
            </ul>
        </nav>
    </header>

    <main class="players-directory-container">
        <h2 class="animate-on-scroll">Players Directory</h2>

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