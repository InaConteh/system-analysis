<?php
session_start();
include 'db_connect.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: players.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM players WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$player = $result->fetch_assoc();

if (!$player) {
    echo "Player not found.";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contract Details | <?php echo htmlspecialchars($player['name']); ?></title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="logo">Football Agency</div>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="players.php">Players</a></li>
                <li class="active-link"><a href="#">Contract</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main class="contract-container animate-on-scroll">
        <section class="player-header">
            <img src="<?php echo htmlspecialchars($player['image_url']); ?>"
                alt="<?php echo htmlspecialchars($player['name']); ?>" class="player-photo">
            <div class="player-info">
                <h1><?php echo htmlspecialchars($player['name']); ?></h1>
                <p>Club: <?php echo htmlspecialchars($player['club']); ?></p>
                <p>Nationality: <?php echo htmlspecialchars($player['nationality'] ?? 'Unknown'); ?></p>
                <!-- Static status for MVP -->
                <p>Status: <span style="color: green;">Active</span></p>
            </div>
            <div class="stat-bar animate-on-scroll delay-100">
                <div class="stat-item">
                    <h3>AGE</h3>
                    <p><?php echo htmlspecialchars($player['age']); ?></p>
                </div>
                <!-- Static Stats for now -->
                <div class="stat-item">
                    <h3>HEIGHT</h3>
                    <p>--</p>
                </div>
                <div class="stat-item">
                    <h3>WEIGHT</h3>
                    <p>--</p>
                </div>
                <div class="stat-item">
                    <h3>Agent</h3>
                    <p>Football Agency</p>
                </div>
            </div>
            <div class="action-buttons">
                <!-- Download Link -->
                <a href="download_report.php?id=<?php echo $player['id']; ?>" class="download-icon"
                    title="Download Report">⬇ Download Report</a>
            </div>
        </section>

        <div class="tab-navigation animate-on-scroll delay-200">
            <div class="tab active">Contract Details</div>
            <div class="tab">Performance</div>
            <div class="tab">Media</div>
        </div>

        <section class="contract-details-grid animate-on-scroll delay-200">
            <div class="contract-box club-contract">
                <h3>Club Contract</h3>
                <div class="contract-item">
                    <p>Current Club</p><strong><?php echo htmlspecialchars($player['club']); ?></strong>
                </div>
                <div class="contract-item">
                    <p>Start Date</p><strong>--/--/----</strong>
                </div>
                <div class="contract-item">
                    <p>Expiry Date</p><strong>--/--/----</strong>
                </div>
                <div class="contract-item">
                    <p>Contract Duration</p>
                    <div class="contract-duration-bar club-duration-bar">
                        <div class="fill" style="width: 60%"></div>
                    </div>
                </div>
            </div>

            <div class="contract-box agency-contract">
                <h3>Agency Contract</h3>
                <div class="contract-item">
                    <p>Agency</p><strong>Football Agency</strong>
                </div>
                <div class="contract-item">
                    <p>Status</p><strong>Active</strong>
                </div>
                <div class="contract-item">
                    <p>Contract Duration</p>
                    <div class="contract-duration-bar agency-duration-bar">
                        <div class="fill" style="width: 40%"></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="video-highlights animate-on-scroll delay-300">
            <h2>Video Highlights</h2>
            <div class="video-placeholder">▶</div>
            <div class="video-thumbnails">
                <!-- Placeholders -->
                <img src="player-1.jpg" class="thumbnail-img">
                <img src="player-2.jpg" class="thumbnail-img">
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>
    <script src="main.js"></script>
</body>

</html>