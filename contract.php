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

// Fetch Videos
$videos = [];
$v_sql = "SELECT * FROM player_videos WHERE player_id = $id";
$v_result = $conn->query($v_sql);
if ($v_result->num_rows > 0) {
    while ($row = $v_result->fetch_assoc()) {
        $videos[] = $row;
    }
}

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
                <p>Status: <span
                        style="font-weight: bold; color: <?php echo ($player['market_status'] == 'Unavailable' || $player['market_status'] == 'Sold') ? 'red' : 'green'; ?>"><?php echo htmlspecialchars($player['market_status']); ?></span>
                </p>
                <?php if ($player['market_status'] == 'For Sale' || $player['market_status'] == 'For Loan'): ?>
                    <p>Market Value: <span
                            style="font-weight: bold; font-size: 1.2em;">$<?php echo number_format($player['market_value']); ?></span>
                    </p>
                <?php endif; ?>
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

                <?php if (isset($_GET['msg'])): ?>
                    <p style="color: green; font-weight: bold; margin-top: 10px;">
                        <?php echo htmlspecialchars($_GET['msg']); ?>
                    </p>
                <?php endif; ?>

                <?php
                $can_bid = (isset($_SESSION['role']) && ($_SESSION['role'] == 'manager' || $_SESSION['role'] == 'agent'));
                // Allow users to bid too if broadly interpreted "user can... bid", but let's stick to manager/agent preference or all logged in users. 
                // Prompt: "user can choose between manager,Agent and just user... place where u can bid". 
                // I'll allow anyone logged in to bid for now to cover "user".
                $can_bid = isset($_SESSION['user_id']); // Simple check for any logged in user
                
                if ($can_bid && ($player['market_status'] == 'For Sale' || $player['market_status'] == 'For Loan')): ?>
                    <div class="bidding-section"
                        style="margin-top: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 8px; background: #fff;">
                        <h3>Place a Bid</h3>
                        <form action="place_bid.php" method="POST">
                            <input type="hidden" name="player_id" value="<?php echo $player['id']; ?>">
                            <div style="margin-bottom: 10px;">
                                <label for="bid_amount">Your Bid ($):</label>
                                <input type="number" name="bid_amount" id="bid_amount"
                                    min="<?php echo $player['market_value']; ?>" required
                                    style="padding: 5px; width: 150px;">
                            </div>
                            <button type="submit" class="cta-button" style="background-color: #007bff;">Submit Bid</button>
                        </form>
                    </div>
                <?php elseif ($player['market_status'] == 'Sold'): ?>
                    <div style="margin-top: 20px; padding: 15px; background: #ffeeba; border-radius: 8px;">
                        <h3>Sold</h3>
                        <p>This player has been sold and is no longer available.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <div class="tab-navigation animate-on-scroll delay-200">
            <div class="tab active" data-target="contract-tab">Contract Details</div>
            <div class="tab" data-target="performance-tab">Performance</div>
            <div class="tab" data-target="media-tab">Media</div>
        </div>

        <div id="contract-tab" class="tab-content active">
            <section class="contract-details-grid animate-on-scroll delay-200">
                <div class="contract-box club-contract">
                    <h3>Club Contract</h3>
                    <div class="contract-item">
                        <p>Current Club</p><strong><?php echo htmlspecialchars($player['club']); ?></strong>
                    </div>
                    <div class="contract-item">
                        <p>Start Date</p>
                        <strong><?php echo $player['contract_start'] ? date("d/m/Y", strtotime($player['contract_start'])) : '--/--/----'; ?></strong>
                    </div>
                    <div class="contract-item">
                        <p>Expiry Date</p>
                        <strong><?php echo $player['contract_end'] ? date("d/m/Y", strtotime($player['contract_end'])) : '--/--/----'; ?></strong>
                    </div>
                    <div class="contract-item">
                        <p>Contract Duration</p>
                        <?php
                        // Simple progress calculation
                        $width = "0%";
                        if ($player['contract_start'] && $player['contract_end']) {
                            $start = strtotime($player['contract_start']);
                            $end = strtotime($player['contract_end']);
                            $now = time();
                            if ($now < $start)
                                $width = "0%";
                            elseif ($now > $end)
                                $width = "100%";
                            else {
                                $total = $end - $start;
                                $elapsed = $now - $start;
                                $percent = ($elapsed / $total) * 100;
                                $width = max(0, min(100, $percent)) . "%";
                            }
                        }
                        ?>
                        <div class="contract-duration-bar club-duration-bar">
                            <div class="fill" style="width: <?php echo $width; ?>"></div>
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
                </div>
            </section>
        </div>

        <div id="performance-tab" class="tab-content" style="display:none; padding: 20px;">
            <h3>Performance Stats</h3>
            <p>No performance data available yet.</p>
        </div>

        <div id="media-tab" class="tab-content" style="display:none;">
            <section class="video-highlights animate-on-scroll delay-300">
                <h2>Video Highlights</h2>
                <?php if (count($videos) > 0): ?>
                    <div style="display: flex; flex-wrap: wrap; gap: 15px; margin-top: 20px;">
                        <?php foreach ($videos as $video): ?>
                            <div class="video-item" style="width: 300px;">
                                <?php
                                $embedUrl = $video['video_url'];
                                $ext = strtolower(pathinfo($embedUrl, PATHINFO_EXTENSION));

                                if (in_array($ext, ['mp4', 'webm', 'ogg', 'mov'])) {
                                    // Local Video File
                                    echo '<video width="100%" height="200" controls>
                                                <source src="' . $embedUrl . '" type="video/' . $ext . '">
                                                Your browser does not support the video tag.
                                              </video>';
                                } elseif (strpos($embedUrl, 'youtube.com/watch?v=') !== false) {
                                    $embedUrl = str_replace('watch?v=', 'embed/', $embedUrl);
                                    echo '<iframe width="100%" height="200" src="' . $embedUrl . '" frameborder="0" allowfullscreen></iframe>';
                                } elseif (strpos($embedUrl, 'youtu.be/') !== false) {
                                    $parts = explode('/', $embedUrl);
                                    $vidId = end($parts);
                                    echo '<iframe width="100%" height="200" src="https://www.youtube.com/embed/' . $vidId . '" frameborder="0" allowfullscreen></iframe>';
                                } else {
                                    echo '<a href="' . $video['video_url'] . '" target="_blank"><img src="' . $video['thumbnail_url'] . '" style="width:100%"><br>Watch Video</a>';
                                }
                                ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>No video highlights available.</p>
                <?php endif; ?>
            </section>
        </div>
    </main>

    <?php include 'footer.php'; ?>
    <script src="main.js"></script>
</body>

</html>