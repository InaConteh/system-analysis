<?php
session_start();
include 'db_connect.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $club = $_POST['club'];
    $age = $_POST['age'];
    $nationality = $_POST['nationality'];
    $market_status = $_POST['market_status'];
    $market_value = $_POST['market_value'];
    $contract_start = !empty($_POST['contract_start']) ? $_POST['contract_start'] : NULL;
    $contract_end = !empty($_POST['contract_end']) ? $_POST['contract_end'] : NULL;

    // Handle File Upload
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Rename file to prevent overwrites and clean filename
    $file_extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
    $new_filename = time() . "_" . bin2hex(random_bytes(4)) . "." . $file_extension;
    $target_file = $target_dir . $new_filename;

    $uploadOk = 1;
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    // Check if image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $message = "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Check file extension
    if (!in_array($file_extension, $allowed_types)) {
        $message = "Sorry, only JPG, JPEG, PNG, GIF & WEBP files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Success upload, insert into DB
            $image_url = $target_file;

            // Updated query to include new fields
            $stmt = $conn->prepare("INSERT INTO players (name, club, age, image_url, nationality, market_status, market_value, contract_start, contract_end) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            // Binding: s=string, i=integer, d=double/float
            // name(s), club(s), age(i), image_url(s), nationality(s), market_status(s), market_value(s), contract_start(s), contract_end(s)
            // Note: market_value might be decimal in DB, but passed as string here works for prepared statements usually. 
            $stmt->bind_param("ssissssss", $name, $club, $age, $image_url, $nationality, $market_status, $market_value, $contract_start, $contract_end);

            if ($stmt->execute()) {
                header("Location: players.php?msg=Player Added Successfully");
                exit();
            } else {
                $message = "Error: " . $stmt->error;
            }
            $stmt->close();

        } else {
            $message = "Sorry, there was an error uploading your file.";
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
    <title>Add Player | Football Agency</title>
    <link rel="stylesheet" href="style.css">
    <!-- Removed inline styles in favor of style.css -->
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
        <h2>Add New Player</h2>
        <?php if ($message)
            echo "<div class='alert alert-error'>$message</div>"; ?>

        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" required>
            </div>
            <div class="form-group">
                <label>Club</label>
                <input type="text" name="club" required>
            </div>
            <div class="form-group">
                <label>Nationality</label>
                <input type="text" name="nationality" required placeholder="e.g. Sierra Leone">
            </div>
            <div class="form-group">
                <label>Age</label>
                <input type="number" name="age" required>
            </div>

            <div class="form-group">
                <label>Market Status</label>
                <select name="market_status">
                    <option value="Unavailable" selected>Unavailable</option>
                    <option value="Free Agent">Free Agent</option>
                    <option value="For Sale">For Sale</option>
                    <option value="For Loan">For Loan</option>
                    <option value="Sold">Sold</option>
                </select>
            </div>

            <div class="form-group">
                <label>Market Value ($)</label>
                <input type="number" name="market_value" step="0.01" placeholder="0.00">
            </div>

            <div class="form-group" style="display: flex; gap: 20px;">
                <div style="flex: 1;">
                    <label>Contract Start</label>
                    <input type="date" name="contract_start">
                </div>
                <div style="flex: 1;">
                    <label>Contract End</label>
                    <input type="date" name="contract_end">
                </div>
            </div>

            <div class="form-group">
                <label>Player Image</label>
                <input type="file" name="image" required accept="image/*">
                <small style="color: #666;">Allowed: JPG, PNG, GIF, WEBP</small>
            </div>

            <button type="submit" class="btn btn-success">Add Player</button>
        </form>
    </div>
    <?php include 'footer.php'; ?>
    <script src="main.js"></script>
</body>

</html>