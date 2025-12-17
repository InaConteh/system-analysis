<?php
session_start();
include 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Football Agency | Home</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="enhanced-hero.css">
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
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main class="home-container">
        <?php
        // Fetch active stats
        $player_count_query = "SELECT COUNT(*) as count FROM players";
        $player_count_result = $conn->query($player_count_query);
        $player_count = ($player_count_result->num_rows > 0) ? $player_count_result->fetch_assoc()['count'] : 0;
        $country_count_query = "SELECT COUNT(DISTINCT nationality) as count FROM players WHERE nationality IS NOT NULL AND nationality != ''";
        $country_count_result = $conn->query($country_count_query);
        $country_count = ($country_count_result->num_rows > 0) ? $country_count_result->fetch_assoc()['count'] : 0;

        // Default values if 0 (visual fallback)
        if ($player_count == 0)
            $player_count = 150;
        if ($country_count == 0)
            $country_count = 25;
        ?>
        <section class="hero-section enhanced-hero" style="background-image: url('images/stadium-bg.png');">
            <!-- Animated particles background -->
            <div class="particles">
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
            </div>

            <!-- Floating football icons -->
            <div class="floating-footballs">
                <span class="football-icon">⚽</span>
                <span class="football-icon">⚽</span>
                <span class="football-icon">⚽</span>
            </div>

            <div class="hero-overlay enhanced-overlay">
                <!-- Animated badge/emblem -->
                <div class="hero-badge">
                    <div class="badge-ring"></div>
                    <div class="badge-content">
                        <span class="badge-icon">⚽</span>
                        <span class="badge-text">FOOTBALL</span>
                    </div>
                </div>

                <!-- Main heading with typing animation -->
                <h1 class="hero-title">
                    <span class="title-line-1">Shaping The Future of</span>
                    <span class="title-line-2">
                        <span class="highlight-text">Football</span>
                        <span class="typed-text">Careers</span>
                    </span>
                </h1>

                <!-- Subtitle with fade-in -->
                <p class="hero-subtitle">Where talent meets opportunity on the global stage</p>

                <!-- Animated CTA buttons -->
                <div class="hero-cta-group">
                    <a href="players.php" class="cta-button cta-primary">
                        <span class="button-icon">👥</span>
                        View Players
                        <span class="button-arrow">→</span>
                    </a>
                    <a href="about.php" class="cta-button cta-secondary">
                        <span class="button-icon">ℹ️</span>
                        Learn More
                    </a>
                </div>

                <!-- Stats counter -->
                <div class="hero-stats" style="margin-bottom: 60px;">
                    <div class="stat-item">
                        <span class="stat-number" data-target="<?php echo $player_count; ?>">0</span>
                        <span class="stat-label">Players</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number" data-target="<?php echo $country_count; ?>">0</span>
                        <span class="stat-label">Countries</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number" data-target="98">0</span>
                        <span class="stat-label">Success Rate</span>
                    </div>
                </div>
            </div>

            <!-- Scroll indicator -->
            <div class="scroll-indicator">
                <span class="scroll-text">Scroll to explore</span>
                <div class="scroll-arrow">↓</div>
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
                        alt="Alpha Turay">
                    <h3>Alpha Turay</h3>
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

    <?php include 'footer.php'; ?>
    <script src="main.js"></script>
</body>

</html>