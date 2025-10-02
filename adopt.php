<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Adopt - Pawradise Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="styles.css" />
    <link rel="stylesheet" href="adopt.css" />
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.html">
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
                    <li class="nav-item"><a class="nav-link" href="index.html">HOME</a></li>
                    <li class="nav-item"><a class="nav-link" href="our-animals.php">OUR ANIMALS</a></li>
                    <li class="nav-item"><a class="nav-link active" href="adopt.html">ADOPT</a></li>
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

    <section class="adopt-hero text-center py-5">
        <div class="container">
            <h1 class="display-4 fw-bold text-white mb-4">Adopt a Pet</h1>
            <p class="lead text-white">Start your journey to giving a deserving animal their forever home today.</p>
        </div>
    </section>

    <section class="adoption-process py-5">
        <div class="container">
            <h2 class="text-center mb-5">Our Adoption Process</h2>
            <div class="row">
                <div class="col-md-4 text-center mb-4">
                    <div class="process-step">
                        <div class="step-number">1</div>
                        <h4>Choose Your Pet</h4>
                        <p>Browse our animals and find the perfect match for your lifestyle and family.</p>
                    </div>
                </div>
                <div class="col-md-4 text-center mb-4">
                    <div class="process-step">
                        <div class="step-number">2</div>
                        <h4>Submit Application</h4>
                        <p>Fill out our detailed application form so we can learn more about you.</p>
                    </div>
                </div>
                <div class="col-md-4 text-center mb-4">
                    <div class="process-step">
                        <div class="step-number">3</div>
                        <h4>Meet & Greet</h4>
                        <p>Schedule a visit to meet your potential new family member in person.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="adoption-form py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="form-container">
                        <h3 class="text-center mb-4">Adoption Application</h3>
                        <form>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="firstName" class="form-label">First Name *</label>
                                    <input type="text" class="form-control" id="firstName" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="lastName" class="form-label">Last Name *</label>
                                    <input type="text" class="form-control" id="lastName" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control" id="email" required>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number *</label>
                                <input type="tel" class="form-control" id="phone" required>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Address *</label>
                                <textarea class="form-control" id="address" rows="3" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="petInterest" class="form-label">Pet You're Interested In</label>
                                <select class="form-select" id="petInterest">
                                    <option value="">Select a pet...</option>
                                    <option value="buddy">Buddy (Golden Retriever)</option>
                                    <option value="whiskers">Whiskers (Tabby Cat)</option>
                                    <option value="snoopy">Snoopy (Beagle)</option>
                                    <option value="fluffy">Fluffy (Persian Cat)</option>
                                    <option value="max">Max (Labrador)</option>
                                    <option value="luna">Luna (Siamese Cat)</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="experience" class="form-label">Pet Ownership Experience</label>
                                <textarea class="form-control" id="experience" rows="3" placeholder="Tell us about your previous pet experience..."></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="homeType" class="form-label">Type of Home</label>
                                <select class="form-select" id="homeType">
                                    <option value="">Select home type...</option>
                                    <option value="house">House</option>
                                    <option value="apartment">Apartment</option>
                                    <option value="condo">Condo</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg">Submit Application</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="bootstrap.js"></script>
    <script src="adopt.js" type="module"></script>
    <script>
        function confirmLogout() {
            if (confirm("Are you sure you want to logout?")) {
                window.location.href = "logout.php";
            }
        }
    </script>
</body>
</html>
