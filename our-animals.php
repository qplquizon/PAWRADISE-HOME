<?php
include 'config.php';
session_start();

try {
    $pets_query = $conn->prepare("SELECT * FROM `pets`");
    $pets_query->execute();
    $pets = $pets_query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching pets: " . $e->getMessage();
    $pets = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Our Animals - Pawradise Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="styles.css" />
    <link rel="stylesheet" href="our-animals.css" />
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
                    <li class="nav-item"><a class="nav-link active" href="our-animals.php">OUR ANIMALS</a></li>
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

    <section class="animals-hero text-center py-5">
        <div class="container">
            <h1 class="display-4 fw-bold text-white mb-4">Meet Our Animals</h1>
            <p class="lead text-white">Discover your perfect companion from our diverse family of cats, dogs, fish, small mammals, horses, and reptiles.</p>
        </div>
    </section>

    <section class="animals-grid py-5">
        <div class="container">
            <div class="row g-4" id="animals-container">
                <?php if(count($pets) > 0): ?>
                    <?php foreach($pets as $pet): ?>
                        <div class="col-lg-4 col-md-6 animal-item" data-category="pets">
                            <div class="animal-card">
                                <div class="animal-image">
                                    <?php if(!empty($pet['image'])): ?>
                                        <img src="<?php echo htmlspecialchars($pet['image']); ?>" alt="<?php echo htmlspecialchars($pet['name']); ?>" class="img-fluid">
                                    <?php else: ?>
                                        <img src="uploads/default-pet.png" alt="Default Pet Image" class="img-fluid">
                                    <?php endif; ?>
                                </div>
                                <div class="animal-info p-3">
                                    <h5 class="animal-name"><?php echo htmlspecialchars($pet['name']); ?></h5>
                                    <p class="animal-breed"><?php echo htmlspecialchars($pet['breed']); ?></p>
                                    <p class="animal-description"><?php echo htmlspecialchars($pet['description']); ?></p>
                                    <span class="badge <?php echo $pet['availability'] ? 'bg-success' : 'bg-secondary'; ?>">
                                        <?php echo $pet['availability'] ? 'Available' : 'Not Available'; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No pets available at the moment.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="adoption-cta text-center py-5 bg-light">
        <div class="container">
            <h2 class="mb-4">Ready to Adopt?</h2>
            <p class="lead mb-4">Take the first step towards giving one of our animals their forever home.</p>
            <a href="adopt.php" class="btn btn-primary btn-lg">Start Adoption Process</a>
        </div>
    </section>
    <script src="bootstrap.js"></script>
    <script src="our-animals.js" type="module"></script>
    <script>
        function confirmLogout() {
            if (confirm("Are you sure you want to logout?")) {
                window.location.href = "logout.php";
            }
        }
    </script>
</body>
</html>
