<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Football Agency | Home</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <header>
        <nav class="navbar">
            <a href="index.php" class="logo">
                <img src="images/logo.png" alt="LionSport Agency">
            </a>
            <ul class="nav-links">
                <li class="active-link"><a href="index.php">Home</a></li>
                <li><a href="players.php">Players</a></li>
                <li><a href="contract.php">Contract</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main class="home-container">
        <section class="hero-section animate-on-scroll" style="background-image: url('images/stadium-bg.png');">
            <div class="hero-overlay">
                <h1 class="animate-on-scroll delay-100">Shaping The Future of<br>Football careers</h1>
                <a href="players.php" class="cta-button animate-on-scroll delay-200">View players</a>
            </div>
        </section>

        <section class="expertise-section">
            <h2 class="animate-on-scroll">Our Expertise</h2>
            <div class="expertise-grid">
                <div class="expertise-card animate-on-scroll delay-100">
                    <span class="icon">🔍</span>
                    <h3>player scouting</h3>
                    <p>Identifying and nurturing the next generation</p>
                </div>
                <div class="expertise-card animate-on-scroll delay-200">
                    <span class="icon">📄</span>
                    <h3>Contract negotiation</h3>
                    <p>Securing the best possible terms and opportunities</p>
                </div>
                <div class="expertise-card animate-on-scroll delay-300">
                    <span class="icon">📣</span>
                    <h3>media management</h3>
                    <p>Building and managing a player's public image</p>
                </div>
            </div>
        </section>

        <section class="featured-players-section">
            <h2 class="animate-on-scroll">Meet Our Star Players</h2>
            <div class="player-row">
                <div class="player-photo-card animate-scale animate-on-scroll"><img src="images/kamara.webp"
                        alt="Kei Kamara">
                    <h3>Kei Kamara</h3>
                </div>
                <div class="player-photo-card animate-scale animate-on-scroll delay-100"><img src="images/Alpha.webp"
                        alt="Alpha Kamara">
                    <h3>Alpha Kamara</h3>
                </div>
                <div class="player-photo-card animate-scale animate-on-scroll delay-200"><img src="images/images.jfif"
                        alt="Mohamed Kamara">
                    <h3>Mohamed Kamara</h3>
                </div>
            </div>
        </section>

        <section class="news-testimonials-section">
            <div class="testimonial-box animate-on-scroll">
                <h3>Trusted by the best</h3>
                <p>"This Agency changed my career. The negotiation skills are genuine..."</p>
                <cite>--John Amadu-- (head coach, Rangers fc)</cite>
            </div>
            <div class="news-box animate-on-scroll delay-100">
                <h3>Latest news</h3>
                <p>kei kamara signed a record breaking deal at La Galaxy until 2029.</p>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>
    <script src="js/main.js"></script>
</body>

</html>