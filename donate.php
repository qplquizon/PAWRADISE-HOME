<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Donate - Pawradise Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="styles.css" />
    <link rel="stylesheet" href="donate.css" />
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
                    <li class="nav-item"><a class="nav-link" href="adopt.php">ADOPT</a></li>
                    <li class="nav-item"><a class="nav-link" href="donate.php">DONATE</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">ABOUT</a></li>
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

    <section class="donate-hero text-center py-5">
        <div class="container">
            <h1 class="display-4 fw-bold text-white mb-4">Support Our Mission</h1>
            <p class="lead text-white">Your generosity helps us provide love, care, and forever homes to animals in need.</p>
        </div>
    </section>

    <section class="impact-stats py-5">
        <div class="container">
            <h2 class="text-center mb-5">Your Impact</h2>
            <div class="row text-center">
                <div class="col-md-3 mb-4">
                    <div class="stat-item">
                        <h3 class="stat-number">500+</h3>
                        <p class="stat-label">Animals Rescued</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="stat-item">
                        <h3 class="stat-number">350+</h3>
                        <p class="stat-label">Successful Adoptions</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="stat-item">
                        <h3 class="stat-number">95%</h3>
                        <p class="stat-label">Adoption Success Rate</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="stat-item">
                        <h3 class="stat-number">24/7</h3>
                        <p class="stat-label">Animal Care</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="donation-form-section py-5">
        <div class="container">
            <h2 class="text-center mb-4">Make a Donation</h2>
            <form id="donationForm" action="admin_panel.php" method="POST" novalidate>
                <div class="mb-3">
                    <label for="name" class="form-label">Name<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" required />
                    <div class="invalid-feedback">Please enter your name.</div>
                </div>
                <div class="mb-3">
                    <label for="contactNumber" class="form-label">Contact Number<span class="text-danger">*</span></label>
                    <input type="tel" class="form-control" id="contactNumber" name="contactNumber" required />
                    <div class="invalid-feedback">Please enter your contact number.</div>
                </div>
                <div class="mb-3">
                    <label for="amount" class="form-label">Amount<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="amount" name="amount" min="0.01" step="0.01" required />
                    <div class="invalid-feedback">Please enter a valid donation amount.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Payment Method<span class="text-danger">*</span></label>
                    <select class="form-select" id="paymentMethod" name="paymentMethod" required>
                        <option value="" selected disabled>Select a payment method</option>
                        <option value="gcash">Gcash</option>
                        <option value="paypal">PayPal</option>
                    </select>
                    <div class="invalid-feedback">Please select a payment method.</div>
                </div>
                <div class="mb-3 qr-code-container" id="gcashQR" style="display:none;">
                    <label class="form-label">Gcash QR Code</label>
                    <div class="qr-placeholder border p-3 text-center">
                        <img src="QR.jpg" alt="Gcash QR Code" style="max-width: 200px; height: auto;" />
                    </div>
                </div>
                <div class="mb-3 qr-code-container" id="paypalQR" style="display:none;">
                    <label class="form-label">PayPal QR Code</label>
                    <div class="qr-placeholder border p-3 text-center">
                        <!-- PayPal QR code image to be added by user -->
                    </div>
                </div>
                <div class="mb-3">
                    <label for="referenceNumber" class="form-label">Reference Number<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="referenceNumber" name="referenceNumber" required />
                    <div class="invalid-feedback">Please enter the reference number.</div>
                </div>
                <button type="submit" class="btn btn-primary">Submit Donation</button>
            </form>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmLogout() {
            if (confirm("Are you sure you want to logout?")) {
                window.location.href = "logout.php";
            }
        }

        // Show/hide QR code based on payment method selection
        document.getElementById('paymentMethod').addEventListener('change', function() {
            var gcashQR = document.getElementById('gcashQR');
            var paypalQR = document.getElementById('paypalQR');
            if (this.value === 'gcash') {
                gcashQR.style.display = 'block';
                paypalQR.style.display = 'none';
            } else if (this.value === 'paypal') {
                gcashQR.style.display = 'none';
                paypalQR.style.display = 'block';
            } else {
                gcashQR.style.display = 'none';
                paypalQR.style.display = 'none';
            }
        });

        // Form validation
        (function () {
            'use strict'
            var form = document.getElementById('donationForm');
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        })();
    </script>
</body>
</html>
