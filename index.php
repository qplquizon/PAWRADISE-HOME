<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Pawradise Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="styles.css" />
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="pawradise-logo.png" alt="Pawradise Logo" class="logo-img d-inline-block align-text-top" />
                <div class="brand-text ms-2">
                    <div class="brand-line1">PAWRADISE</div>
                    <div class="brand-line2">HOME</div>
                </div>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item"><a class="nav-link active" href="index.php">HOME</a></li>
                    <li class="nav-item"><a class="nav-link" href="our-animals.php">OUR ANIMALS</a></li>
                    <li class="nav-item"><a class="nav-link" href="adopt.html">ADOPT</a></li>
                    <li class="nav-item"><a class="nav-link" href="donate.html">DONATE</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.html">ABOUT</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="User Profile">
                            <?php if(isset($_SESSION['user_id'])): ?>
                                <span class="me-2"><?php echo htmlspecialchars($_SESSION['user_email']); ?></span>
                            <?php endif; ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#556" viewBox="0 0 24 24">
                                <circle cx="12" cy="8" r="4" />
                                <path d="M4 20c0-4 8-4 8-4s8 0 8 4v1H4v-1z" />
                            </svg>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="profileDropdown">
                            <?php if(isset($_SESSION['user_id'])): ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#" onclick="confirmLogout()">Logout</a></li>
                            <?php else: ?>
                                <li><a class="dropdown-item" href="Login.php">Login</a></li>
                                <li><a class="dropdown-item" href="register.php">Register</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <section class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title">Welcome to Pawradise Home</h1>
            <p class="hero-subtitle">Where your Forever Companion awaits</p>
            <p class="hero-description">Discover loving animals waiting for their forever homes. <br> we have the perfect companion for you.</p>
            <div class="hero-buttons">
                <a href="our-animals.php" class="btn btn-primary btn-lg me-3">Meet Our Animals</a>
                <a href="adopt.html" class="btn btn-outline-light btn-lg">Start Adoption</a>
            </div>
        </div>
    </section>

    <section class="featured-animals py-5">
        <div class="container">
            <h2 class="section-title text-center mb-5">Featured Friends</h2>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="animal-card">
                        <div class="animal-image">
                            <img src="https://images.esquiremag.ph/esquiremagph/images/2020/05/20/native-dog-breed-philippines-claws-07.jpg" alt="Tiger Commando" class="img-fluid">
                        </div>
                        <div class="animal-info p-3">
                            <h5 class="animal-name">Tiger Commando</h5>
                            <p class="animal-breed">Native</p>
                            <p class="animal-description">Friendly and energetic 2-year-old who loves playing fetch.</p>
                            <span class="badge bg-primary">Available</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="animal-card">
                        <div class="animal-image">
                            <img src="https://cdn.prod.website-files.com/61bbcec54a71bb14e2611c3a/66f2e01debe46444617bbc5c_66c38827de147c4896062852_62d981ff7afe26e3a32fbd29_62d19e5768e09b606317da6b_Shih%25252520Tzu%25252520Breed%25252520Profile_Hero.webp" alt="Shih Tzu" class="img-fluid">
                        </div>
                        <div class="animal-info p-3">
                            <h5 class="animal-name">Whitey</h5>
                            <p class="animal-breed">Shih Tzu</p>
                            <p class="animal-description">Sweet and affectionate dog who zooms a lot.</p>
                            <span class="badge bg-primary">Available</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 animal-item" data-category="dogs">
                    <div class="animal-card">
                        <div class="animal-image">
                            <img src="https://a-z-animals.com/media/2022/06/American-Quarter-Horse-stallion.jpg" alt="Quarter Horse" class="img-fluid">

                            <img src="https://images.unsplash.com/photo-1583337130417-3346a1be7dee?w=400&h=300&fit=crop" alt="Beagle" class="img-fluid">

                        </div>
                        <div class="animal-info p-3">
                            <h5 class="animal-name">Snoopy</h5>
                            <p class="animal-breed">Beagle</p>
                            <p class="animal-description">Curious and friendly 3-year-old beagle with lots of energy and a great sense of smell.</p>
                            <span class="badge bg-primary">Available</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4">
                <a href="our-animals.php" class="btn btn-outline-primary">View All Animals</a>
            </div>
        </div>
    </section>

    <section class="cta-section py-5 bg-primary text-white">
        <div class="container text-center">
            <h2 class="mb-4">Ready to Find Your Perfect Companion?</h2>
            <p class="lead mb-4">Take the first step towards giving an animal their forever home.</p>
            <div class="cta-buttons">
                <a href="adopt.html" class="btn btn-light btn-lg me-3">Start Adoption Process</a>
                <a href="donate.html" class="btn btn-outline-light btn-lg">Make a Donation</a>
            </div>
        </div>
    </section>

    <footer class="footer-section py-4 bg-dark text-white">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h5>Pawradise Home</h5>
                    <p>Where Every Paw Finds Its Paradise</p>
                </div>
                <div class="col-lg-3"> 
                    <h6>Contact</h6>
                    <p>Phone: 63+ 9389382916</p>
                    <p>Email: pawgrammers@gmail.com</p>
                </div>
            </div>
        </div>
    </footer>
    <script src="bootstrap.js"></script>
    <script>
        function confirmLogout() {
            if (confirm("Are you sure you want to logout?")) {
                window.location.href = "logout.php";
            }
        }
    </script>
</body>
</html>
