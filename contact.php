<?php
session_start();

// Handle form submission
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'db_connect.php';
    
    // Sanitize and validate input
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    // Validation
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Name is required.";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    
    if (empty($message)) {
        $errors[] = "Message is required.";
    }
    
    if (empty($errors)) {
        // Insert into database
        $stmt = $conn->prepare("INSERT INTO contact_submissions (name, email, phone, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $phone, $message);
        
        if ($stmt->execute()) {
            $success_message = "Thank you for contacting us! We'll get back to you soon.";
            // Clear form data
            $name = $email = $phone = $message = '';
        } else {
            $error_message = "Sorry, there was an error submitting your message. Please try again.";
        }
        
        $stmt->close();
        $conn->close();
    } else {
        $error_message = implode("<br>", $errors);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Football Agency</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <nav class="navbar">
            <a href="index.php" class="logo">
                <img src="images/logo.png" alt="LionSport Agency">
            </a>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="players.php">Players</a></li>
                <li><a href="about.php">About</a></li>
                <li class="active-link"><a href="contact.php">Contact</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main class="contact-page-container">
        <section class="contact-hero-section animate-on-scroll">
            <div class="hero-overlay">
                <h1 class="animate-on-scroll delay-100">Get In Touch</h1>
                <p class="hero-subtitle animate-on-scroll delay-200">We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
            </div>
        </section>

        <section class="contact-content-section">
            <div class="contact-grid">
                <div class="contact-info animate-on-scroll">
                    <h2>Contact Information</h2>
                    <div class="info-item">
                        <span class="icon">📧</span>
                        <div>
                            <h3>Email</h3>
                            <p>info@footballagency.com</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <span class="icon">📞</span>
                        <div>
                            <h3>Phone</h3>
                            <p>+1 234 567 890</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <span class="icon">📍</span>
                        <div>
                            <h3>Address</h3>
                            <p>123 Football Street<br>Sports City, SC 12345</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <span class="icon">🕒</span>
                        <div>
                            <h3>Business Hours</h3>
                            <p>Monday - Friday: 9:00 AM - 6:00 PM<br>Saturday: 10:00 AM - 4:00 PM</p>
                        </div>
                    </div>
                </div>

                <div class="contact-form-wrapper animate-on-scroll delay-100">
                    <h2>Send Us a Message</h2>
                    
                    <?php if ($success_message): ?>
                        <div class="alert alert-success"><?php echo $success_message; ?></div>
                    <?php endif; ?>
                    
                    <?php if ($error_message): ?>
                        <div class="alert alert-error"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="contact.php" class="contact-form">
                        <div class="form-group">
                            <label for="name">Full Name *</label>
                            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($phone ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Message *</label>
                            <textarea id="message" name="message" rows="6" required><?php echo htmlspecialchars($message ?? ''); ?></textarea>
                        </div>
                        
                        <button type="submit" class="cta-button">Send Message</button>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>
    <script src="main.js"></script>
</body>

</html>
