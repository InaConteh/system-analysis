<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | Football Agency</title>
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
                <li class="active-link"><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main class="about-page-container">
        <section class="about-hero-section animate-on-scroll">
            <div class="hero-overlay">
                <h1 class="animate-on-scroll delay-100">About Our Agency</h1>
                <p class="hero-subtitle animate-on-scroll delay-200">Connecting talent with opportunity since 2010</p>
            </div>
        </section>

        <section class="mission-section">
            <div class="content-wrapper animate-on-scroll">
                <h2>Our Mission</h2>
                <p class="mission-text">
                    At Football Agency, we are dedicated to shaping the future of football careers. We believe in
                    nurturing talent,
                    building strong relationships, and creating opportunities that transform lives. Our mission is to
                    provide
                    comprehensive representation and support to football players, helping them achieve their dreams both
                    on and off the field.
                </p>
            </div>
        </section>

        <section class="values-section">
            <h2 class="animate-on-scroll">Our Core Values</h2>
            <div class="values-grid">
                <div class="value-card animate-on-scroll delay-100">
                    <span class="icon">🎯</span>
                    <h3>Excellence</h3>
                    <p>We strive for excellence in everything we do, from player representation to contract negotiation.
                    </p>
                </div>
                <div class="value-card animate-on-scroll delay-200">
                    <span class="icon">🤝</span>
                    <h3>Integrity</h3>
                    <p>We operate with transparency and honesty, building trust with players, clubs, and partners.</p>
                </div>
                <div class="value-card animate-on-scroll delay-300">
                    <span class="icon">💪</span>
                    <h3>Dedication</h3>
                    <p>We are committed to our players' success, providing 24/7 support and guidance throughout their
                        careers.</p>
                </div>
                <div class="value-card animate-on-scroll">
                    <span class="icon">🌟</span>
                    <h3>Innovation</h3>
                    <p>We embrace new technologies and strategies to stay ahead in the competitive world of football.
                    </p>
                </div>
            </div>
        </section>

        <section class="team-section">
            <h2 class="animate-on-scroll">Meet Our Team</h2>
            <div class="team-grid">
                <div class="team-member animate-on-scroll delay-100">
                    <div class="member-photo">
                        <img src="images/team/Dir.jpeg" alt="Ina Conteh - Founder & CEO"
                            onerror="this.src='images/team/default-avatar.png'">
                    </div>
                    <h3>Ina Conteh</h3>
                    <p class="role">Founder & CEO</p>
                    <p class="bio">With over 5 years of experience in football management, Ina leads our agency with
                        vision and passion.</p>
                </div>
                <div class="team-member animate-on-scroll delay-200">
                    <div class="member-photo">
                        <img src="images/team/Head.jpeg" alt="Mark Ellie - Head of Player Relations"
                            onerror="this.src='images/team/default-avatar.png'">
                    </div>
                    <h3>Mark Ellie</h3>
                    <p class="role">Head of Player Relations</p>
                    <p class="bio">Mark ensures our players receive personalized attention and support throughout their
                        careers.</p>
                </div>
                <div class="team-member animate-on-scroll delay-300">
                    <div class="member-photo">
                        <img src="images/team/Contrat.jpeg" alt="Sahid Conteh - Contract Negotiation Specialist"
                            onerror="this.src='images/team/default-avatar.png'">
                    </div>
                    <h3>Sahid Conteh</h3>
                    <p class="role">Contract Negotiation Specialist</p>
                    <p class="bio">Sahid's expertise in contract law ensures our players get the best possible deals.
                    </p>
                </div>
            </div>
        </section>

        <section class="achievements-section">
            <h2 class="animate-on-scroll">Our Achievements</h2>
            <div class="achievements-grid">
                <div class="achievement-card animate-on-scroll delay-100">
                    <h3 class="achievement-number">150+</h3>
                    <p>Players Represented</p>
                </div>
                <div class="achievement-card animate-on-scroll delay-200">
                    <h3 class="achievement-number">$500M+</h3>
                    <p>Total Contract Value</p>
                </div>
                <div class="achievement-card animate-on-scroll delay-300">
                    <h3 class="achievement-number">25+</h3>
                    <p>Countries Worldwide</p>
                </div>
                <div class="achievement-card animate-on-scroll">
                    <h3 class="achievement-number">98%</h3>
                    <p>Client Satisfaction</p>
                </div>
            </div>
        </section>

        <section class="cta-section animate-on-scroll">
            <h2>Ready to Take Your Career to the Next Level?</h2>
            <p>Join our roster of talented players and let us help you achieve your dreams.</p>
            <a href="contact.php" class="cta-button">Get In Touch</a>
        </section>
    </main>

    <?php include 'footer.php'; ?>
    <script src="main.js"></script>
</body>

</html>