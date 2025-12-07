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

    // Handle File Upload
    $target_dir = "uploads/";
    // Ensure filename is unique or handles special chars to prevent issues
    $filename = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $filename;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

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

    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Success upload, insert into DB
            $image_url = $target_file; // Store path relative to root

            $stmt = $conn->prepare("INSERT INTO players (name, club, age, image_url, nationality) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssiss", $name, $club, $age, $image_url, $nationality);

            if ($stmt->execute()) {
                header("Location: players.php");
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
            background: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }

        .btn:hover {
            background: #218838;
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
        <h2>Add New Player</h2>
        <?php if ($message)
            echo "<p style='color:red;'>$message</p>"; ?>
        <!-- Added enctype for file upload -->
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
                <label>Player Image</label>
                <input type="file" name="image" required accept="image/*">
            </div>
            <button type="submit" class="btn">Add Player</button>
        </form>
    </div>
    <?php include 'footer.php'; ?>
    <script src="main.js"></script>
</body>

</html>