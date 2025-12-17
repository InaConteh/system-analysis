<?php
session_start();
require_once 'db_connect.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Handle mark as read
if (isset($_GET['mark_read']) && is_numeric($_GET['mark_read'])) {
    $id = intval($_GET['mark_read']);
    $stmt = $conn->prepare("UPDATE contact_submissions SET status = 'read' WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin_contacts.php");
    exit();
}

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM contact_submissions WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin_contacts.php");
    exit();
}

// Fetch all contact submissions
$result = $conn->query("SELECT * FROM contact_submissions ORDER BY submitted_at DESC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Submissions | Admin</title>
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
                <li><a href="contact.php">Contact</a></li>
                <li class="active-link"><a href="admin_contacts.php">Contact Submissions</a></li>
                <li><a href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
            </ul>
        </nav>
    </header>

    <main class="admin-container">
        <div class="admin-header">
            <h1>Contact Form Submissions</h1>
            <p>Manage and respond to contact inquiries</p>
        </div>

        <div class="submissions-container">
            <?php if ($result->num_rows > 0): ?>
                <table class="submissions-table">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Message</th>
                            <th>Submitted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr class="<?php echo $row['status'] === 'new' ? 'status-new' : 'status-read'; ?>">
                                <td>
                                    <span class="status-badge status-<?php echo $row['status']; ?>">
                                        <?php echo ucfirst($row['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><a
                                        href="mailto:<?php echo htmlspecialchars($row['email']); ?>"><?php echo htmlspecialchars($row['email']); ?></a>
                                </td>
                                <td><?php echo htmlspecialchars($row['phone'] ?: 'N/A'); ?></td>
                                <td class="message-cell">
                                    <div class="message-preview">
                                        <?php echo htmlspecialchars(substr($row['message'], 0, 100)); ?>        <?php echo strlen($row['message']) > 100 ? '...' : ''; ?>
                                    </div>
                                    <div class="message-full" style="display: none;">
                                        <?php echo nl2br(htmlspecialchars($row['message'])); ?></div>
                                    <button class="btn-toggle-message" onclick="toggleMessage(this)">Show Full</button>
                                </td>
                                <td><?php echo date('M d, Y H:i', strtotime($row['submitted_at'])); ?></td>
                                <td class="actions-cell">
                                    <?php if ($row['status'] === 'new'): ?>
                                        <a href="admin_contacts.php?mark_read=<?php echo $row['id']; ?>" class="btn-mark-read"
                                            title="Mark as Read">✓</a>
                                    <?php endif; ?>
                                    <a href="admin_contacts.php?delete=<?php echo $row['id']; ?>" class="btn-delete-small"
                                        onclick="return confirm('Are you sure you want to delete this submission?');"
                                        title="Delete">🗑️</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-submissions">
                    <p>No contact submissions yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include 'footer.php'; ?>

    <script src="main.js"></script>
    <script>
        function toggleMessage(btn) {
            const cell = btn.closest('.message-cell');
            const preview = cell.querySelector('.message-preview');
            const full = cell.querySelector('.message-full');

            if (full.style.display === 'none') {
                preview.style.display = 'none';
                full.style.display = 'block';
                btn.textContent = 'Show Less';
            } else {
                preview.style.display = 'block';
                full.style.display = 'none';
                btn.textContent = 'Show Full';
            }
        }
    </script>
</body>

</html>
<?php
$conn->close();
?>