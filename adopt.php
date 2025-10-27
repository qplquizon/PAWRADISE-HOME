<?php
session_start();
include 'config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);


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

    $all_pets = [
        ['id' => 1, 'name' => 'Buddy', 'breed' => 'Golden Retriever', 'type' => 'dog', 'availability' => 1],
        ['id' => 2, 'name' => 'Whiskers', 'breed' => 'Siamese Cat', 'type' => 'cat', 'availability' => 1],
        ['id' => 3, 'name' => 'Max', 'breed' => 'Labrador', 'type' => 'dog', 'availability' => 1],
        ['id' => 4, 'name' => 'Luna', 'breed' => 'Persian Cat', 'type' => 'cat', 'availability' => 1]
    ];
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        $errors[] = 'You must be logged in to submit an adoption application!';
    } else {
        $petInterest = trim($_POST['petInterest'] ?? '');
        $firstName = trim($_POST['firstName'] ?? '');
        $lastName = trim($_POST['lastName'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $occupation = trim($_POST['occupation'] ?? '');
        $homeType = trim($_POST['livingPlaceType'] ?? '');
        $petsAllowed = trim($_POST['petsAllowed'] ?? '');
        $familySupport = trim($_POST['familySupport'] ?? '');
        $homeVisitPermission = trim($_POST['homeVisitPermission'] ?? '');
        $otherPetsType = trim($_POST['otherPetsType'] ?? '');
        $otherPetsCount = trim($_POST['otherPetsCount'] ?? '');
        $spayedNeutered = trim($_POST['spayedNeutered'] ?? '');
        $spayedNeuteredDetails = trim($_POST['spayedNeuteredDetails'] ?? '');
        $moveWithPets = trim($_POST['moveWithPets'] ?? '');
        $monetarySupport = trim($_POST['monetarySupport'] ?? '');
        $secureHome = trim($_POST['secureHome'] ?? '');
        $responsibilityCommitment = trim($_POST['responsibilityCommitment'] ?? '');
        $careFamiliarity = trim($_POST['careFamiliarity'] ?? '');
        $termsAgreed = isset($_POST['termsAgreed']) ? 1 : 0;

        $errors = [];

        if (empty($petInterest)) $errors[] = 'Please select a pet.';
        if (empty($firstName)) $errors[] = 'First name is required.';
        if (empty($lastName)) $errors[] = 'Last name is required.';
        if (empty($email)) $errors[] = 'Email is required.';
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email format.';
        if (empty($address)) $errors[] = 'Address is required.';
        if (!$termsAgreed) $errors[] = 'You must agree to the terms and conditions.';

        if (empty($errors)) {
            try {
                $stmt = $conn->prepare("INSERT INTO adoption_requests (pet_interest, first_name, last_name, address, phone, email, occupation, home_type, pets_allowed, family_support, home_visit_permission, other_pets_type, other_pets_count, spayed_neutered, spayed_neutered_details, move_with_pets, monetary_support, secure_home, responsibility_commitment, experience) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$petInterest, $firstName, $lastName, $address, $phone, $email, $occupation, $homeType, $petsAllowed, $familySupport, $homeVisitPermission, $otherPetsType, $otherPetsCount, $spayedNeutered, $spayedNeuteredDetails, $moveWithPets, $monetarySupport, $secureHome, $responsibilityCommitment, $careFamiliarity]);


                $success_message = 'Adoption form submitted successfully!';
            } catch (PDOException $e) {
                $errors[] = 'Database error: ' . $e->getMessage();
            }
        }
    }


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

                        <form method="POST" action="adopt.php" novalidate onsubmit="return validateTerms();">

                            <h4 class="mb-3">Pet you're interested</h4>
                            <div class="mb-3">
                                <label for="petInterest" class="form-label">Select a Pet *</label>
                                <select class="form-select" id="petInterest" name="petInterest" required>
                                    <option value="">Choose a pet...</option>

                                </select>
                            </div>

 
                            <h4 class="mb-3">APPLICANT INFORMATION</h4>
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
                                <label for="address" class="form-label">Address *</label>
                                <textarea class="form-control" id="address" name="address" rows="3" required><?php echo htmlspecialchars($_SESSION['adoption_data']['address'] ?? ''); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($_SESSION['adoption_data']['phone'] ?? ''); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" required value="<?php echo htmlspecialchars($_SESSION['adoption_data']['email'] ?? ''); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="occupation" class="form-label">Occupation</label>
                                <input type="text" class="form-control" id="occupation" name="occupation" value="<?php echo htmlspecialchars($_SESSION['adoption_data']['occupation'] ?? ''); ?>">
                            </div>

                            <!-- LIVING OCCUPATION -->
                            <h4 class="mb-3">LIVING OCCUPATION</h4>
                            <div class="mb-3">
                                <label for="livingPlaceType" class="form-label">What type of place do you live in?</label>
                                <select class="form-select" id="livingPlaceType" name="livingPlaceType">
                                    <option value="">Select...</option>
                                    <option value="house" <?php echo ($_SESSION['adoption_data']['livingPlaceType'] ?? '') == 'house' ? 'selected' : ''; ?>>House</option>
                                    <option value="condo" <?php echo ($_SESSION['adoption_data']['livingPlaceType'] ?? '') == 'condo' ? 'selected' : ''; ?>>Condo</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="petsAllowed" class="form-label">Are you allowed to keep pets?</label>
                                <select class="form-select" id="petsAllowed" name="petsAllowed">
                                    <option value="">Select...</option>
                                    <option value="yes" <?php echo ($_SESSION['adoption_data']['petsAllowed'] ?? '') == 'yes' ? 'selected' : ''; ?>>Yes</option>
                                    <option value="no" <?php echo ($_SESSION['adoption_data']['petsAllowed'] ?? '') == 'no' ? 'selected' : ''; ?>>No</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="familySupport" class="form-label">Do all members of the family support your decision to adopt a pet?</label>
                                <select class="form-select" id="familySupport" name="familySupport">
                                    <option value="">Select...</option>
                                    <option value="yes" <?php echo ($_SESSION['adoption_data']['familySupport'] ?? '') == 'yes' ? 'selected' : ''; ?>>Yes</option>
                                    <option value="no" <?php echo ($_SESSION['adoption_data']['familySupport'] ?? '') == 'no' ? 'selected' : ''; ?>>No</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="homeVisitPermission" class="form-label">Do we have permission to visit your home?</label>
                                <select class="form-select" id="homeVisitPermission" name="homeVisitPermission">
                                    <option value="">Select...</option>
                                    <option value="yes" <?php echo ($_SESSION['adoption_data']['homeVisitPermission'] ?? '') == 'yes' ? 'selected' : ''; ?>>Yes</option>
                                    <option value="no" <?php echo ($_SESSION['adoption_data']['homeVisitPermission'] ?? '') == 'no' ? 'selected' : ''; ?>>No</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="otherPetsType" class="form-label">If you have other pets, is your pet a CAT or a DOG?</label>
                                <select class="form-select" id="otherPetsType" name="otherPetsType">
                                    <option value="">Select...</option>
                                    <option value="cat" <?php echo ($_SESSION['adoption_data']['otherPetsType'] ?? '') == 'cat' ? 'selected' : ''; ?>>Cat</option>
                                    <option value="dog" <?php echo ($_SESSION['adoption_data']['otherPetsType'] ?? '') == 'dog' ? 'selected' : ''; ?>>Dog</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="otherPetsCount" class="form-label">If you own more than one pet, kindly indicate how many cats and dogs you own</label>
                                <textarea class="form-control" id="otherPetsCount" name="otherPetsCount" rows="2"><?php echo htmlspecialchars($_SESSION['adoption_data']['otherPetsCount'] ?? ''); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="spayedNeutered" class="form-label">Is your pet spayed/neutered?</label>
                                <select class="form-select" id="spayedNeutered" name="spayedNeutered">
                                    <option value="">Select...</option>
                                    <option value="yes" <?php echo ($_SESSION['adoption_data']['spayedNeutered'] ?? '') == 'yes' ? 'selected' : ''; ?>>Yes</option>
                                    <option value="no" <?php echo ($_SESSION['adoption_data']['spayedNeutered'] ?? '') == 'no' ? 'selected' : ''; ?>>No</option>
                                    <option value="other" <?php echo ($_SESSION['adoption_data']['spayedNeutered'] ?? '') == 'other' ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>
                            <div class="mb-3" id="spayedNeuteredDetailsDiv" style="display: none;">
                                <label for="spayedNeuteredDetails" class="form-label">If you own more than one pet, kindly choose other and indicate how many are spayed/neutered and how many are not yet</label>
                                <textarea class="form-control" id="spayedNeuteredDetails" name="spayedNeuteredDetails" rows="2"><?php echo htmlspecialchars($_SESSION['adoption_data']['spayedNeuteredDetails'] ?? ''); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="moveWithPets" class="form-label">If you were to move, would you take your pets with you?</label>
                                <select class="form-select" id="moveWithPets" name="moveWithPets">
                                    <option value="">Select...</option>
                                    <option value="yes" <?php echo ($_SESSION['adoption_data']['moveWithPets'] ?? '') == 'yes' ? 'selected' : ''; ?>>Yes</option>
                                    <option value="no" <?php echo ($_SESSION['adoption_data']['moveWithPets'] ?? '') == 'no' ? 'selected' : ''; ?>>No</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="monetarySupport" class="form-label">Do you have the monetary ability to support the pet (food, grooming, toys, medical expenses)?</label>
                                <select class="form-select" id="monetarySupport" name="monetarySupport">
                                    <option value="">Select...</option>
                                    <option value="yes" <?php echo ($_SESSION['adoption_data']['monetarySupport'] ?? '') == 'yes' ? 'selected' : ''; ?>>Yes</option>
                                    <option value="no" <?php echo ($_SESSION['adoption_data']['monetarySupport'] ?? '') == 'no' ? 'selected' : ''; ?>>No</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="secureHome" class="form-label">Can you ensure us that your home is sufficiently secure to keep the pet indoors?</label>
                                <select class="form-select" id="secureHome" name="secureHome">
                                    <option value="">Select...</option>
                                    <option value="yes" <?php echo ($_SESSION['adoption_data']['secureHome'] ?? '') == 'yes' ? 'selected' : ''; ?>>Yes</option>
                                    <option value="no" <?php echo ($_SESSION['adoption_data']['secureHome'] ?? '') == 'no' ? 'selected' : ''; ?>>No</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="responsibilityCommitment" class="form-label">Are you and your family committed to taking full responsibility for your pet's health and welfare for the rest of its life, which could be 10 years or more?</label>
                                <select class="form-select" id="responsibilityCommitment" name="responsibilityCommitment">
                                    <option value="">Select...</option>
                                    <option value="yes" <?php echo ($_SESSION['adoption_data']['responsibilityCommitment'] ?? '') == 'yes' ? 'selected' : ''; ?>>Yes</option>
                                    <option value="no" <?php echo ($_SESSION['adoption_data']['responsibilityCommitment'] ?? '') == 'no' ? 'selected' : ''; ?>>No</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="careFamiliarity" class="form-label">Are you familiar with taking care (handling/grooming/medical) of the pet you choose to adopt?</label>
                                <select class="form-select" id="careFamiliarity" name="careFamiliarity">
                                    <option value="">Select...</option>
                                    <option value="yes" <?php echo ($_SESSION['adoption_data']['careFamiliarity'] ?? '') == 'yes' ? 'selected' : ''; ?>>Yes</option>
                                    <option value="no" <?php echo ($_SESSION['adoption_data']['careFamiliarity'] ?? '') == 'no' ? 'selected' : ''; ?>>No</option>
                                </select>
                            </div>


                            <h4 class="mb-3">Terms and Conditions</h4>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="termsAgreed" name="termsAgreed" value="1" <?php echo isset($_SESSION['adoption_data']['termsAgreed']) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="termsAgreed">I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">terms and conditions</a> *</label>
                                <?php if (in_array('You must agree to the terms and conditions.', $errors ?? [])): ?>
                                    <div class="text-danger">You must agree to the terms and conditions.</div>
                                <?php endif; ?>
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


    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>All of this information will be encoded and will be protected. Also, there is no guarantee that it can be accepted.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

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
        function validateTerms() {
            const termsCheckbox = document.getElementById('termsAgreed');
            if (!termsCheckbox.checked) {
                alert('You must agree to the terms and conditions before submitting the form.');
                return false;
            }
            return true;
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const petSelect = document.getElementById('petInterest');
            let petsData = [];
            fetch('pets_api.php')
                .then(response => response.json())
                .then(data => {
                    console.log('Pets data fetched:', data);
                    petsData = data;
                    if (Array.isArray(data) && data.length > 0) {
                        data.forEach(pet => {

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

 
            const spayedNeuteredSelect = document.getElementById('spayedNeutered');
            const spayedNeuteredDetailsDiv = document.getElementById('spayedNeuteredDetailsDiv');
            spayedNeuteredSelect.addEventListener('change', function() {
                if (this.value === 'other') {
                    spayedNeuteredDetailsDiv.style.display = 'block';
                } else {
                    spayedNeuteredDetailsDiv.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
