document.addEventListener("DOMContentLoaded", () => {
    // --- Scroll Animations ---
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1 // Trigger when 10% of the element is visible
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target); // Only animate once
            }
        });
    }, observerOptions);

    const animatedElements = document.querySelectorAll('.animate-on-scroll');
    animatedElements.forEach(el => observer.observe(el));

    // --- Sticky Navbar Effect ---
    const navbar = document.querySelector('.navbar');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // --- Tab Navigation ---
    const tabs = document.querySelectorAll('.tab');
    const tabContents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            // Remove active class from all tabs
            tabs.forEach(t => t.classList.remove('active'));
            // Add active class to clicked tab
            tab.classList.add('active');

            // Hide all tab contents
            tabContents.forEach(content => {
                content.style.display = 'none';
                content.classList.remove('active');
            });

            // Show target tab content
            const targetId = tab.getAttribute('data-target');
            if (targetId) {
                const targetContent = document.getElementById(targetId);
                if (targetContent) {
                    targetContent.style.display = 'block';
                    // Trigger reflow or small timeout to allow animation if needed, but display block is key
                    targetContent.classList.add('active');
                }
            }
        });
    });

    // --- Enhanced Hero Features ---
    
    // 1. Stats Counter Animation
    const statsContainer = document.querySelector('.hero-stats');
    const statNumbers = document.querySelectorAll('.stat-number');
    
    if (statsContainer && statNumbers.length > 0) {
        let activated = false;
        
        const startCounting = () => {
            statNumbers.forEach(stat => {
                const target = +stat.getAttribute('data-target');
                const duration = 2000; // 2 seconds
                const increment = target / (duration / 16); // 60fps
                
                let current = 0;
                const updateCount = () => {
                    current += increment;
                    if (current < target) {
                        stat.textContent = Math.ceil(current);
                        requestAnimationFrame(updateCount);
                    } else {
                        stat.textContent = target + (stat.nextElementSibling.innerText.includes('Rate') ? '%' : '+');
                    }
                };
                updateCount();
            });
        };

        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !activated) {
                    startCounting();
                    activated = true;
                }
            });
        }, { threshold: 0.5 });
        
        statsObserver.observe(statsContainer);
    }

    // 2. Parallax Mouse Effect
    const heroSection = document.querySelector('.enhanced-hero');
    const particles = document.querySelectorAll('.particle');
    const footballs = document.querySelectorAll('.football-icon');
    
    if (heroSection) {
        heroSection.addEventListener('mousemove', (e) => {
            const x = e.clientX / window.innerWidth;
            const y = e.clientY / window.innerHeight;
            
            // Move particles slightly
            particles.forEach((particle, index) => {
                const speed = (index + 1) * 2;
                const xOffset = (x - 0.5) * speed * 10;
                const yOffset = (y - 0.5) * speed * 10;
                particle.style.transform = `translate(${xOffset}px, ${yOffset}px)`;
            });
            
            // Move footballs more noticeably (opposite direction)
            footballs.forEach((ball, index) => {
                const speed = (index + 1) * 5;
                const xOffset = -(x - 0.5) * speed * 2;
                const yOffset = -(y - 0.5) * speed * 2;
                ball.style.transform = `translate(${xOffset}px, ${yOffset}px) rotate(${xOffset}deg)`;
            });
        });
    }

    // 3. Scroll Down Indicator
    const scrollArrow = document.querySelector('.scroll-indicator');
    if (scrollArrow) {
        scrollArrow.addEventListener('click', () => {
            const nextSection = heroSection.nextElementSibling;
            if (nextSection) {
                nextSection.scrollIntoView({ behavior: 'smooth' });
            }
        });
    }
});
