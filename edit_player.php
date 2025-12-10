<?php
session_start();
include 'db_connect.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: players.php");
    exit();
}

$message = "";

// Fetch existing data
$stmt = $conn->prepare("SELECT * FROM players WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$player = $result->fetch_assoc();

if (!$player) {
    echo "Player not found.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- VIDEO HANDLING ---

    // Handle Video Deletion
    if (isset($_POST['delete_video_id'])) {
        $video_id = $_POST['delete_video_id'];
        $conn->query("DELETE FROM player_videos WHERE id=$video_id");
        $message = "Video deleted.";
    }

    // Handle Video Addition
    if (!empty($_POST['new_video_url']) || !empty($_FILES['video_file']['name'])) {
        $video_url = '';
        $thumbnail_url = '';

        // Priority 1: File Upload
        if (!empty($_FILES['video_file']['name'])) {
            $target_vid_dir = "uploads/videos/";
            if (!file_exists($target_vid_dir)) {
                mkdir($target_vid_dir, 0777, true);
            }

            $vid_filename = time() . "_" . basename($_FILES['video_file']['name']);
            $target_vid_file = $target_vid_dir . $vid_filename;

            // Allow certain formats
            $fileType = strtolower(pathinfo($target_vid_file, PATHINFO_EXTENSION));
            $allowed = ['mp4', 'webm', 'ogg', 'mov'];

            if (in_array($fileType, $allowed)) {
                if (move_uploaded_file($_FILES['video_file']['tmp_name'], $target_vid_file)) {
                    $video_url = $target_vid_file;
                    $thumbnail_url = "uploads/default_video_thumb.jpg"; // Placeholder for local videos
                } else {
                    $message = "Error uploading video file.";
                }
            } else {
                $message = "Invalid video format. Allowed: mp4, webm, ogg, mov";
            }
        }
        // Priority 2: URL
        elseif (!empty($_POST['new_video_url'])) {
            $video_url = $_POST['new_video_url'];
            // Simple logic to extract YouTube ID if possible for thumbnail, otherwise default
            if (strpos($video_url, 'youtube.com') !== false || strpos($video_url, 'youtu.be') !== false) {
                // heuristic for youtube thumb
                $thumbnail_url = "https://img.youtube.com/vi/" . basename($video_url) . "/0.jpg";
            } else {
                $thumbnail_url = "uploads/default_video_thumb.jpg";
            }
        }

        if ($video_url) {
            $stmt_vid = $conn->prepare("INSERT INTO player_videos (player_id, video_url, thumbnail_url) VALUES (?, ?, ?)");
            $stmt_vid->bind_param("iss", $id, $video_url, $thumbnail_url);
            if ($stmt_vid->execute()) {
                $message = "Video added successfully.";
            } else {
                $message = "Error adding video: " . $conn->error;
            }
        }
    }

    // --- PLAYER INFO UPDATE ---
    if (isset($_POST['update_player'])) {
        $name = $_POST['name'];
        $club = $_POST['club'];
        $age = $_POST['age'];
        $nationality = $_POST['nationality'];
        $market_status = $_POST['market_status'];
        $market_value = $_POST['market_value'];
        $contract_start = !empty($_POST['contract_start']) ? $_POST['contract_start'] : NULL;
        $contract_end = !empty($_POST['contract_end']) ? $_POST['contract_end'] : NULL;
        $image_url = $player['image_url'];

        // Handle File Upload if new file selected
        if (!empty($_FILES["image"]["name"])) {
            $target_dir = "uploads/";
            // Ensure dir exists
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $filename = basename($_FILES["image"]["name"]);
            $target_file = $target_dir . $filename;
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check !== false) {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $image_url = $target_file;
                } else {
                    $message = "Sorry, there was an error uploading your file.";
                }
            } else {
                $message = "File is not an image.";
            }
        }

        if (!$message || $message == "Video added successfully." || $message == "Video deleted.") {
            // Only update if no error (or if the message was just a success message for video)
            // Note: If uploading bad video failed, we might still want to update player data? 
            // Let's assume yes, but usually separate forms are better. Here they are combined or separate?
            // The UI below has separate forms for Video and Player. 
            // If 'update_player' is set, it's the main form.

            $update_stmt = $conn->prepare("UPDATE players SET name=?, club=?, age=?, nationality=?, market_status=?, market_value=?, contract_start=?, contract_end=?, image_url=? WHERE id=?");
            $update_stmt->bind_param("ssissssssi", $name, $club, $age, $nationality, $market_status, $market_value, $contract_start, $contract_end, $image_url, $id);

            if ($update_stmt->execute()) {
                header("Location: edit_player.php?id=$id&msg=Player Updated");
                exit();
            } else {
                $message = "Error updating record: " . $conn->error;
            }
        }
    }
}

// Fetch Videos (Refresh list)
$videos_result = $conn->query("SELECT * FROM player_videos WHERE player_id = $id");
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Player | Football Agency</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box; /* Fix padding issues */
        }

        .btn {
            width: 100%;
            padding: 10px;
            background: #ffc107;
            color: black;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            font-weight: bold;
        }

        .btn:hover {
            background: #e0a800;
        }
        
        .btn-delete {
            background: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
        }
        .btn-delete:hover {
            background: #c82333;
        }

        .current-img {
            display: block;
            width: 100px;
            margin-top: 10px;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="logo">Admin Panel</div>
            <ul class="nav-links">
                <li><a href="players.php">Back to List</a></li>
            </ul>
        </nav>
    </header>

    <div class="form-container animate-on-scroll">
        <h2>Edit Player</h2>
        <?php if ($message)
            echo "<p style='color:red;'>$message</p>"; ?>
        <?php if (isset($_GET['msg']))
            echo "<p style='color:green;'>" . htmlspecialchars($_GET['msg']) . "</p>"; ?>
        
        <form method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="update_player" value="1">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($player['name']); ?>" required>
            </div>
            <div class="form-group">
                <label>Club</label>
                <input type="text" name="club" value="<?php echo htmlspecialchars($player['club']); ?>" required>
            </div>
            <div class="form-group">
                <label>Nationality</label>
                <input type="text" name="nationality" value="<?php echo htmlspecialchars($player['nationality'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label>Age</label>
                <input type="number" name="age" value="<?php echo htmlspecialchars($player['age']); ?>" required>
            </div>
            <div class="form-group">
                <label>Market Status</label>
                <select name="market_status">
                    <option value="Unavailable" <?php if ($player['market_status'] == 'Unavailable')
                        echo 'selected'; ?>>Unavailable</option>
                    <option value="Free Agent" <?php if ($player['market_status'] == 'Free Agent')
                        echo 'selected'; ?>>Free Agent</option>
                    <option value="For Sale" <?php if ($player['market_status'] == 'For Sale')
                        echo 'selected'; ?>>For Sale</option>
                    <option value="For Loan" <?php if ($player['market_status'] == 'For Loan')
                        echo 'selected'; ?>>For Loan</option>
                    <option value="Sold" <?php if ($player['market_status'] == 'Sold')
                        echo 'selected'; ?>>Sold</option>
                </select>
            </div>
            <div class="form-group">
                <label>Market Value ($)</label>
                <input type="number" name="market_value" value="<?php echo htmlspecialchars($player['market_value']); ?>" step="0.01">
            </div>

            <div class="form-group" style="display: flex; gap: 10px;">
                <div style="flex: 1;">
                    <label>Contract Start</label>
                    <input type="date" name="contract_start" value="<?php echo htmlspecialchars($player['contract_start']); ?>">
                </div>
                <div style="flex: 1;">
                    <label>Contract End</label>
                    <input type="date" name="contract_end" value="<?php echo htmlspecialchars($player['contract_end']); ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Current Image</label>
                <?php if ($player['image_url']): ?>
                        <img src="<?php echo htmlspecialchars($player['image_url']); ?>" class="current-img">
                <?php else: ?>
                        <p>No image set.</p>
                <?php endif; ?>
                <label style="margin-top:10px;">Change Image (Leave blank to keep current)</label>
                <input type="file" name="image" accept="image/*">
            </div>
            <button type="submit" class="btn">Update Player</button>
        </form>

        <!-- Video Management Section -->
        <div style="margin-top: 30px; border-top: 2px solid #eee; padding-top: 20px;">
            <h3>Video Highlights</h3>
            
            <!-- List existing videos -->
            <?php if ($videos_result->num_rows > 0): ?>
                    <?php while ($vid = $videos_result->fetch_assoc()): ?>
                            <div style="background: #f9f9f9; padding: 10px; margin-bottom: 5px; display: flex; justify-content: space-between; align-items: center; border-radius: 4px;">
                                <span style="font-size: 0.9em; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 350px;">
                                    <?php echo htmlspecialchars($vid['video_url']); ?>
                                </span>
                                <form method="POST" style="margin:0;">
                                    <input type="hidden" name="delete_video_id" value="<?php echo $vid['id']; ?>">
                                    <button type="submit" class="btn-delete" onclick="return confirm('Are you sure?');">Delete</button>
                                </form>
                            </div>
                    <?php endwhile; ?>
            <?php else: ?>
                    <p>No videos added.</p>
            <?php endif; ?>

            <!-- Add New Video Form -->
            <h4 style="margin-top: 20px;">Add New Video</h4>
            <form method="POST" action="" enctype="multipart/form-data" style="background: #f1f1f1; padding: 15px; border-radius: 8px;">
                <div class="form-group">
                    <label>Video URL (YouTube/External)</label>
                    <input type="text" name="new_video_url" placeholder="https://youtube.com/...">
                </div>
                <div class="form-group">
                    <label>OR Upload Video File (MP4, WebM)</label>
                    <input type="file" name="video_file" accept="video/*">
                </div>
                <button type="submit" class="btn" style="background-color: #28a745;">Add Video</button>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="main.js"></script>
</body>
</html>