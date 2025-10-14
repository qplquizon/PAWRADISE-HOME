<?php
session_start();
include 'config.php';

// Create table if not exists
try {
    $sql = "CREATE TABLE IF NOT EXISTS adoption_requests (
        id INT AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(255) NOT NULL,
        last_name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        address TEXT NOT NULL,
        pet_interest VARCHAR(255),
        experience TEXT,
        home_type VARCHAR(50),
        status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
        submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $conn->exec($sql);
} catch (PDOException $e) {
    // Table might already exist or error, continue
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

error_reporting(E_ALL);
ini_set('display_errors', 1);

error_reporting(E_ALL);
ini_set('display_errors', 1);

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch pets from pets_api.php via curl
$all_pets = [];
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "http://localhost/Pawradise/pets_api.php");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);
$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

if ($http_code == 200 && $response) {
    $all_pets = json_decode($response, true);
    if (!is_array($all_pets)) {
        $all_pets = [];
    }
} else {
    // Fallback sample pets on error
    $all_pets = [
        ['id' => 1, 'name' => 'Buddy', 'breed' => 'Golden Retriever', 'type' => 'dog', 'availability' => 1],
        ['id' => 2, 'name' => 'Whiskers', 'breed' => 'Siamese Cat', 'type' => 'cat', 'availability' => 1],
        ['id' => 3, 'name' => 'Max', 'breed' => 'Labrador', 'type' => 'dog', 'availability' => 1],
        ['id' => 4, 'name' => 'Luna', 'breed' => 'Persian Cat', 'type' => 'cat', 'availability' => 1]
    ];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        $errors[] = 'You must be logged in to submit an adoption application!';
    } else {
        $firstName = trim($_POST['firstName'] ?? '');
        $lastName = trim($_POST['lastName'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $petInterest = trim($_POST['petInterest'] ?? '');
        $experience = trim($_POST['experience'] ?? '');
        $homeType = trim($_POST['homeType'] ?? '');

        $errors = [];

        if (empty($firstName)) $errors[] = 'First name is required.';
        if (empty($lastName)) $errors[] = 'Last name is required.';
        if (empty($email)) $errors[] = 'Email is required.';
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email format.';
        if (empty($phone)) $errors[] = 'Phone number is required.';
        if (empty($address)) $errors[] = 'Address is required.';
        if (empty($experience)) $errors[] = 'Pet ownership experience is required.';

        if (empty($errors)) {
            try {
                $stmt = $conn->prepare("INSERT INTO adoption_requests (first_name, last_name, email, phone, address, pet_interest, experience, home_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$firstName, $lastName, $email, $phone, $address, $petInterest, $experience, $homeType]);
                // Set success message
                $success_message = 'Adoption form submitted successfully!';
            } catch (PDOException $e) {
                $errors[] = 'Database error: ' . $e->getMessage();
            }
        }
    }

    // If errors, store in session to display
    if (!empty($errors)) {
        $_SESSION['adoption_errors'] = $errors;
        $_SESSION['adoption_data'] = $_POST;
    }
}
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
                    <li class="nav-item"><a class="nav-link active" href="adopt.php">ADOPT</a></li>
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
                        <?php if (!isset($_SESSION['user_id'])): ?>
                            <div class="alert alert-warning text-center">
                                <h4>You're not logged in</h4>
                                <p>Please <a href="Login.php">login</a> to submit an adoption application.</p>
                            </div>
                        <?php else: ?>

                        <?php if (isset($success_message)): ?>
                            <div class="alert alert-success text-center mb-4">
                                <h4><?php echo htmlspecialchars($success_message); ?></h4>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="adopt.php" novalidate>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="firstName" class="form-label">First Name *</label>
                                    <input type="text" class="form-control <?php echo in_array('First name is required.', $errors ?? []) ? 'is-invalid' : ''; ?>" id="firstName" name="firstName" required value="<?php echo htmlspecialchars($_SESSION['adoption_data']['firstName'] ?? ''); ?>">
                                    <div class="invalid-feedback">First name is required.</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="lastName" class="form-label">Last Name *</label>
                                    <input type="text" class="form-control <?php echo in_array('Last name is required.', $errors ?? []) ? 'is-invalid' : ''; ?>" id="lastName" name="lastName" required value="<?php echo htmlspecialchars($_SESSION['adoption_data']['lastName'] ?? ''); ?>">
                                    <div class="invalid-feedback">Last name is required.</div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number *</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Address *</label>
                                <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="petInterest" class="form-label">Pet You're Interested In</label>
                                <select class="form-select" id="petInterest" name="petInterest">
                                    <option value="">Select a pet...</option>
                                    <!-- Options will be populated by JavaScript -->
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="experience" class="form-label">Pet Ownership Experience *</label>
                                <textarea class="form-control <?php echo in_array('Pet ownership experience is required.', $errors ?? []) ? 'is-invalid' : ''; ?>" id="experience" name="experience" rows="3" placeholder="Tell us about your previous pet experience..." required><?php echo htmlspecialchars($_SESSION['adoption_data']['experience'] ?? ''); ?></textarea>
                                <div class="invalid-feedback">Pet ownership experience is required.</div>
                            </div>

                            <div class="mb-3">
                                <label for="homeType" class="form-label">Type of Home</label>
                                <select class="form-select" id="homeType" name="homeType">
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
                        <?php endif; ?>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const petSelect = document.getElementById('petInterest');
            fetch('pets_api.php')
                .then(response => response.json())
                .then(data => {
                    console.log('Pets data fetched:', data);
                    if (Array.isArray(data) && data.length > 0) {
                        data.forEach(pet => {
                            // Removed availability filter for debugging
                            const option = document.createElement('option');
                            option.value = pet.id;
                            option.textContent = `${pet.name} (${pet.breed})`;
                            petSelect.appendChild(option);
                        });
                    } else {
                        const option = document.createElement('option');
                        option.textContent = 'No pets available';
                        option.disabled = true;
                        petSelect.appendChild(option);
                    }
                })
                .catch(error => {
                    const option = document.createElement('option');
                    option.textContent = 'Error loading pets';
                    option.disabled = true;
                    petSelect.appendChild(option);
                    console.error('Error fetching pets:', error);
                });
        });
    </script>
</body>
</html>
