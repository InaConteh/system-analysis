<?php
include 'db_connect.php';

$username = "SuperAdmin";
$email = "admin@agency.com";
$password = "AdminSecret123!";
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$role = 'admin';

// Check if admin already exists
$check_sql = "SELECT * FROM users WHERE email='$email'";
$result = $conn->query($check_sql);

if ($result->num_rows > 0) {
    echo "Admin account already exists.<br>";
} else {
    $sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$hashed_password', '$role')";
    if ($conn->query($sql) === TRUE) {
        echo "Admin account created successfully.<br>";
        echo "Email: $email<br>";
        echo "Password: $password<br>";
    } else {
        echo "Error creating admin account: " . $conn->error;
    }
}

echo "<br><a href='login.php'>Go to Login</a>";
$conn->close();
?>