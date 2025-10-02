<?php
include 'config.php';

$message = [];

if(isset($_POST['submit'])){
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $amount = filter_var($_POST['amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    if(empty($name) || empty($email) || empty($amount) || $amount <= 0){
        $message[] = 'Please fill all fields correctly!';
    } else {
        $insert = $conn->prepare("INSERT INTO donations (name, email, amount) VALUES (?, ?, ?)");
        if($insert->execute([$name, $email, $amount])){
            $message[] = 'Donation submitted successfully!';
        } else {
            $message[] = 'Donation failed!';
        }
    }
}
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
                    <li class="nav-item"><a class="nav-link" href="index.php">HOME</a></li>
                    <li class="nav-item"><a class="nav-link" href="our-animals.html">OUR ANIMALS</a></li>
                    <li class="nav-item"><a class="nav-link" href="adopt.html">ADOPT</a></li>
                    <li class="nav-item"><a class="nav-link active" href="donate.php">DONATE</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.html">ABOUT</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" title="User Profile">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#556" viewBox="0 0 24 24">
                                <circle cx="12" cy="8" r="4" />
                                <path d="M4 20c0-4 8-4 8-4s8 0 8 4v1H4v-1z" />
                            </svg>
                        </a>
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

    <section class="donation-form py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white text-center">
                            <h3>Make a Donation</h3>
                        </div>
                        <div class="card-body">
                            <?php
                            if(isset($message)){
                                foreach($message as $msg){
                                    $close_icon = (strpos($msg, 'successfully') === false) ? '<i class="fas fa-times" onclick="this.parentElement.remove();"></i>' : '';
                                    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                            '.$msg.'
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                          </div>';
                                }
                            }
                            ?>
                            <form action="" method="POST">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Donation Amount ($)</label>
                                    <input type="number" class="form-control" id="amount" name="amount" step="0.01" min="1" required>
                                </div>
                                <button type="submit" name="submit" class="btn btn-primary w-100">Donate Now</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
