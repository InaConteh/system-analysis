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
    $name = $_POST['name'];
    $club = $_POST['club'];
    $age = $_POST['age'];
    $nationality = $_POST['nationality'];
    $image_url = $player['image_url']; // Default to existing

    // Handle File Upload if new file selected
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "uploads/";
        $filename = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $filename;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

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

    if (!$message) {
        $update_stmt = $conn->prepare("UPDATE players SET name=?, club=?, age=?, nationality=?, image_url=? WHERE id=?");
        $update_stmt->bind_param("ssissi", $name, $club, $age, $nationality, $image_url, $id);

        if ($update_stmt->execute()) {
            header("Location: players.php");
            exit();
        } else {
            $message = "Error updating record: " . $conn->error;
        }
    }
}
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
            max-width: 500px;
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

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .btn {
            width: 100%;
            padding: 10px;
            background: #ffc107;
            color: black;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }

        .btn:hover {
            background: #e0a800;
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
        <form method="POST" action="" enctype="multipart/form-data">
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
                <!-- Handle case where nationality column might not be populated yet for old records -->
                <input type="text" name="nationality"
                    value="<?php echo htmlspecialchars($player['nationality'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label>Age</label>
                <input type="number" name="age" value="<?php echo htmlspecialchars($player['age']); ?>" required>
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
    </div>
    <?php include 'footer.php'; ?>
    <script src="main.js"></script>
</body>

</html>