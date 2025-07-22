<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CaptureSync | Home of Freelance Photographers</title>
    <link rel="stylesheet" href="landing.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="main-header">
        <div class="logo">
            <img src="logo/C__7_-removebg-preview.png" alt="CaptureSync Logo">
            <span>CAPTURESYNC</span>
        </div>
        <nav>
            <ul>
                <li><a href="#hero">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#how">How It Works</a></li>
                <li><a href="#testimonials">Testimonials</a></li>
                <li><a href="main.php">Login</a></li>
                <li><a href="choose_signup.php" class="signup-btn">Sign Up</a></li>
            </ul>
        </nav>
    </header>

    <section class="hero" id="hero">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1>Capture Life's Best Moments</h1>
            <p class="tagline">Connecting you with top freelance photographers for every occasion.</p>
            <a href="choose_signup.php" class="cta-btn">Get Started</a>
        </div>
    </section>

    <section class="about-us" id="about">
        <h2>About Us</h2>
        <p>CaptureSync is the premier platform connecting talented freelance photographers with clients seeking high-quality, creative photography services. Whether you're looking to capture life's special moments or showcase your professional work, CaptureSync makes it easy to find the perfect match.</p>
    </section>

    <section class="how-it-works" id="how">
        <h2>How It Works</h2>
        <div class="steps">
            <div class="step">
                <div class="step-icon">📝</div>
                <h3>1. Sign Up</h3>
                <p>Create a free account as a client or photographer and set up your profile in minutes.</p>
            </div>
            <div class="step">
                <div class="step-icon">🔍</div>
                <h3>2. Discover</h3>
                <p>Browse portfolios, read reviews, and find the right photographer for your needs.</p>
            </div>
            <div class="step">
                <div class="step-icon">💬</div>
                <h3>3. Connect & Book</h3>
                <p>Message, negotiate, and book your chosen photographer securely through our platform.</p>
            </div>
            <div class="step">
                <div class="step-icon">📸</div>
                <h3>4. Capture & Enjoy</h3>
                <p>Enjoy a seamless photography experience and cherish your memories forever!</p>
  </div>
</div>
    </section>

    <section class="testimonials" id="testimonials">
        <h2>What Our Users Say</h2>
        <div class="testimonial-cards">
            <div class="testimonial-card">
                <img src="landingpageimg/download (2).jpg" class="testimonial-img" alt="Hazel M.">
                <p class="testimonial-text">"CaptureSync made it so easy to find the perfect photographer for my event! Highly recommended."</p>
                <div class="stars">★★★★★</div>
                <div class="extra-info">Hazel M. – NYC</div>
  </div>
            <div class="testimonial-card">
                <img src="landingpageimg/download (3).jpg" class="testimonial-img" alt="Emma D.">
                <p class="testimonial-text">"A seamless experience from start to finish. The quality of photographers is top-notch!"</p>
                <div class="stars">★★★★★</div>
                <div class="extra-info">Emma D. – UK</div>
</div>
            <div class="testimonial-card">
                <img src="landingpageimg/bg.jpg" class="testimonial-img" alt="Jamaica P.">
                <p class="testimonial-text">"I love how easy it is to connect and book. CaptureSync is my go-to for all my shoots."</p>
                <div class="stars">★★★★★</div>
                <div class="extra-info">Jamaica P. – PH</div>
  </div>
</div>
    </section>

    <footer class="footer">
        <div class="footer-content">
            <span>&copy; <?php echo date('Y'); ?> CaptureSync. All rights reserved.</span>
            <div class="footer-links">
                <a href="#" id="privacy-link">Privacy Policy</a>
                <a href="#" id="terms-link">Terms of Service</a>
                <a href="mailto:support@capturesync.com">Contact</a>
            </div>
        </div>
    </footer>

    <!-- Privacy Policy Modal -->
    <div id="privacy-modal" class="modal">
        <div class="modal-content">
            <span class="close" id="close-privacy">&times;</span>
            <h2>Privacy Policy</h2>
            <p>At CaptureSync, your privacy is important to us. We collect only the information necessary to provide our services, such as your name, email, and booking details. We do not sell or share your personal data with third parties except as required to deliver our services or comply with the law. All data is stored securely and you may request deletion of your account at any time. By using CaptureSync, you consent to our data practices as described here.</p>
            <ul>
                <li>We use cookies to enhance your experience and analyze site usage.</li>
                <li>Your data is encrypted and protected.</li>
                <li>You may contact us at any time for questions about your privacy.</li>
            </ul>
        </div>
    </div>
    <!-- Terms of Service Modal -->
    <div id="terms-modal" class="modal">
        <div class="modal-content">
            <span class="close" id="close-terms">&times;</span>
            <h2>Terms of Service</h2>
            <p>By using CaptureSync, you agree to abide by our terms. Users must provide accurate information and respect the rights of others. Photographers and clients are responsible for their own interactions and agreements. CaptureSync is not liable for disputes between users, but we encourage respectful and professional conduct at all times.</p>
            <ul>
                <li>Do not use CaptureSync for unlawful or harmful activities.</li>
                <li>Respect copyright and intellectual property rights.</li>
                <li>We reserve the right to suspend accounts for violations.</li>
            </ul>
        </div>
    </div>
    <script>
    // Modal logic
    const privacyLink = document.getElementById('privacy-link');
    const termsLink = document.getElementById('terms-link');
    const privacyModal = document.getElementById('privacy-modal');
    const termsModal = document.getElementById('terms-modal');
    const closePrivacy = document.getElementById('close-privacy');
    const closeTerms = document.getElementById('close-terms');

    privacyLink.onclick = function(e) {
        e.preventDefault();
        privacyModal.style.display = 'block';
    };
    termsLink.onclick = function(e) {
        e.preventDefault();
        termsModal.style.display = 'block';
    };
    closePrivacy.onclick = function() {
        privacyModal.style.display = 'none';
    };
    closeTerms.onclick = function() {
        termsModal.style.display = 'none';
    };
    window.onclick = function(event) {
        if (event.target === privacyModal) privacyModal.style.display = 'none';
        if (event.target === termsModal) termsModal.style.display = 'none';
    };
    </script>
</body>
</html>