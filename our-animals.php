<?php
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
include 'config.php';
session_start();

$is_admin = isset($_SESSION['user_id']); // Assuming logged in users have admin access

try {
    if ($is_admin) {
        $pets_query = $conn->prepare("SELECT *, COALESCE(featured, 0) as featured FROM `pets`");
    } else {
        $pets_query = $conn->prepare("SELECT * FROM `pets` WHERE availability = 1");
    }
    $pets_query->execute();
    $pets = $pets_query->fetchAll(PDO::FETCH_ASSOC);
    // Ensure uniqueness by reindexing with id to prevent replication
    $unique_pets = [];
    foreach ($pets as $pet) {
        $unique_pets[$pet['id']] = $pet;
    }
    $pets = array_values($unique_pets);
    // Default type to 'other' if not set for proper categorization
    foreach ($pets as &$pet) {
        if (!isset($pet['type']) || empty($pet['type'])) {
            $pet['type'] = 'other';
        }
    }
    $available_pets_count = 0;
    foreach ($pets as $pet) {
        if ($pet['availability'] == 1) {
            $available_pets_count++;
        }
    }
    echo "<!-- Available pets count in our-animals.php: $available_pets_count -->";
} catch (PDOException $e) {
    echo "Error fetching pets: " . $e->getMessage();
    $pets = [];
}

if(isset($_POST['update_pet'])){
    $pet_id = $_POST['pet_id'];
    $name = $_POST['name'];
    $breed = $_POST['breed'];
    $description = $_POST['description'];
    $availability = isset($_POST['availability']) ? 1 : 0;
    $type = $_POST['type'];
    $featured = isset($_POST['featured']) ? 1 : 0;

    $image = '';
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        // Make image name unique to prevent overwrites
        $unique_name = time() . '_' . basename($image_name);
        $image_path = 'uploads/' . $unique_name;
        if(move_uploaded_file($image_tmp, $image_path)){
            $image = $image_path;
        }
    }

    try {
        if($image != ''){
            // Update with new image
            $update = $conn->prepare("UPDATE `pets` SET name = ?, breed = ?, description = ?, image = ?, availability = ?, type = ?, featured = ? WHERE id = ?");
            $update->execute([$name, $breed, $description, $image, $availability, $type, $featured, $pet_id]);
        } else {
            // Update without changing image
            $update = $conn->prepare("UPDATE `pets` SET name = ?, breed = ?, description = ?, availability = ?, type = ?, featured = ? WHERE id = ?");
            $update->execute([$name, $breed, $description, $availability, $type, $featured, $pet_id]);
        }
        $_SESSION['message'] = "Pet updated successfully!";
        header("Location: our-animals.php?v=" . time());
        exit();
    } catch (PDOException $e) {
        // Handle missing columns individually
        if (strpos($e->getMessage(), 'Unknown column \'type\'') !== false) {
            // Update without type, but with featured
            if($image != ''){
                $update = $conn->prepare("UPDATE `pets` SET name = ?, breed = ?, description = ?, image = ?, availability = ?, featured = ? WHERE id = ?");
                $update->execute([$name, $breed, $description, $image, $availability, $featured, $pet_id]);
            } else {
                $update = $conn->prepare("UPDATE `pets` SET name = ?, breed = ?, description = ?, availability = ?, featured = ? WHERE id = ?");
                $update->execute([$name, $breed, $description, $availability, $featured, $pet_id]);
            }
            $_SESSION['message'] = "Pet updated successfully! (Note: Type field may not have been updated due to database schema)";
        } elseif (strpos($e->getMessage(), 'Unknown column \'featured\'') !== false) {
            // Update without featured
            if($image != ''){
                $update = $conn->prepare("UPDATE `pets` SET name = ?, breed = ?, description = ?, image = ?, availability = ?, type = ? WHERE id = ?");
                $update->execute([$name, $breed, $description, $image, $availability, $type, $pet_id]);
            } else {
                $update = $conn->prepare("UPDATE `pets` SET name = ?, breed = ?, description = ?, availability = ?, type = ? WHERE id = ?");
                $update->execute([$name, $breed, $description, $availability, $type, $pet_id]);
            }
            $_SESSION['message'] = "Pet updated successfully! (Note: Featured field may not have been updated due to database schema)";
        } else {
            $_SESSION['error'] = "Error updating pet: " . $e->getMessage();
        }
    }
    header("Location: our-animals.php?v=" . time());
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <title>Our Animals - Pawradise Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>" />
    <link rel="stylesheet" href="our-animals.css?v=<?php echo time(); ?>" />
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <?php if(isset($_SESSION['message'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($_SESSION['message']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>
            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
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
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
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

    <div style="display:none;"><?php echo time(); ?></div>
    <section class="animals-hero text-center py-5">
        <div class="container">
            <h1 class="display-4 fw-bold text-white mb-4">Meet Our Animals</h1>
            <p class="lead text-white">Discover your perfect companion from our diverse family of cats, dogs, fish, small mammals, horses, and reptiles.</p>
        </div>
    </section>

    <section class="animals-grid py-5">
        <div class="container">
            <!-- Search Input -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search by name, breed, or type..." autocomplete="off">
                </div>
            </div>

            <!-- Filter Buttons -->
            <div class="d-flex justify-content-center mb-4">
                <div class="btn-group" role="group" aria-label="Animal Filter">
                    <button type="button" class="btn btn-outline-primary filter-btn active" data-filter="all">All</button>
                    <button type="button" class="btn btn-outline-primary filter-btn" data-filter="dogs">Dogs</button>
                    <button type="button" class="btn btn-outline-primary filter-btn" data-filter="cats">Cats</button>
                    <button type="button" class="btn btn-outline-primary filter-btn" data-filter="others">Other Animals</button>
                </div>
            </div>

            <?php
            $dogs = array_filter($pets, function($pet) { return strtolower($pet['type']) === 'dog'; });
            $cats = array_filter($pets, function($pet) { return strtolower($pet['type']) === 'cat'; });
            $others = array_filter($pets, function($pet) { return !in_array(strtolower($pet['type']), ['dog', 'cat']); });
            ?>

            <h3 id="dogs-header">Dogs</h3>
            <div class="row g-4" id="dogs-container">
                <?php if(count($dogs) > 0): ?>
                    <?php foreach($dogs as $pet): ?>
                        <div class="col-sm-6 col-md-4 col-lg-4 animal-item" data-name="<?php echo htmlspecialchars(strtolower($pet['name'])); ?>" data-breed="<?php echo htmlspecialchars(strtolower($pet['breed'])); ?>" data-type="<?php echo htmlspecialchars(strtolower($pet['type'])); ?>" data-availability="<?php echo $pet['availability'] ? '1' : '0'; ?>">
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
                                    <?php if($is_admin && $pet['availability'] == 0): ?>
                                        <button type="button" class="btn btn-warning btn-sm mt-2 edit-pet-btn" data-pet='<?php echo json_encode($pet); ?>'>Edit</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No dogs available at the moment.</p>
                <?php endif; ?>
            </div>

            <h3 id="cats-header">Cats</h3>
            <div class="row g-4" id="cats-container">
                <?php if(count($cats) > 0): ?>
                    <?php foreach($cats as $pet): ?>
                        <div class="col-sm-6 col-md-4 col-lg-4 animal-item" data-name="<?php echo htmlspecialchars(strtolower($pet['name'])); ?>" data-breed="<?php echo htmlspecialchars(strtolower($pet['breed'])); ?>" data-type="<?php echo htmlspecialchars(strtolower($pet['type'])); ?>" data-availability="<?php echo $pet['availability'] ? '1' : '0'; ?>">
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
                                    <?php if($is_admin && $pet['availability'] == 0): ?>
                                        <button type="button" class="btn btn-warning btn-sm mt-2 edit-pet-btn" data-pet='<?php echo json_encode($pet); ?>'>Edit</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No cats available at the moment.</p>
                <?php endif; ?>
            </div>

            <h3 id="others-header">Other Animals</h3>
            <div class="row g-4" id="others-container">
                <?php if(count($others) > 0): ?>
                    <?php foreach($others as $pet): ?>
                        <div class="col-sm-6 col-md-4 col-lg-4 animal-item" data-name="<?php echo htmlspecialchars(strtolower($pet['name'])); ?>" data-breed="<?php echo htmlspecialchars(strtolower($pet['breed'])); ?>" data-type="<?php echo htmlspecialchars(strtolower($pet['type'])); ?>" data-availability="<?php echo $pet['availability'] ? '1' : '0'; ?>">
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
                                    <?php if($is_admin && $pet['availability'] == 0): ?>
                                        <button type="button" class="btn btn-warning btn-sm mt-2 edit-pet-btn" data-pet='<?php echo json_encode($pet); ?>'>Edit</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No other animals available at the moment.</p>
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

    <!-- Edit Pet Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Pet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="our-animals.php" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" id="edit_pet_id" name="pet_id" value="" />
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Pet Name</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required />
                        </div>
                        <div class="mb-3">
                            <label for="edit_breed" class="form-label">Breed</label>
                            <input type="text" class="form-control" id="edit_breed" name="breed" required />
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_image" class="form-label">Picture</label>
                            <input type="file" class="form-control" id="edit_image" name="image" accept="image/*" />
                            <small class="form-text text-muted">Leave blank to keep existing image.</small>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="edit_availability" name="availability" />
                            <label class="form-check-label" for="edit_availability">Available for Adoption</label>
                        </div>
                        <div class="mb-3">
                            <label for="edit_type" class="form-label">Animal Type</label>
                            <select class="form-control" id="edit_type" name="type" required>
                                <option value="dog">Dog</option>
                                <option value="cat">Cat</option>
                                <option value="other">Other Animals</option>
                            </select>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="edit_featured" name="featured" />
                            <label class="form-check-label" for="edit_featured">Feature in Main Page</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="update_pet" class="btn btn-primary">Update Pet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="bootstrap.js"></script>
    <script src="our-animals.js" type="module"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filter functionality
            const filterButtons = document.querySelectorAll('.filter-btn');
            const animalItems = document.querySelectorAll('.animal-item');
            const sections = [
                { header: document.getElementById('dogs-header'), container: document.getElementById('dogs-container') },
                { header: document.getElementById('cats-header'), container: document.getElementById('cats-container') },
                { header: document.getElementById('others-header'), container: document.getElementById('others-container') }
            ];

            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const filter = this.getAttribute('data-filter');

                    // Remove active class from all buttons
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    // Add active class to clicked button
                    this.classList.add('active');

                    // Show/hide animal items based on filter
                    animalItems.forEach(item => {
                        const type = item.getAttribute('data-type');
                        if (filter === 'all' ||
                            (filter === 'dogs' && type === 'dog') ||
                            (filter === 'cats' && type === 'cat') ||
                            (filter === 'others' && type !== 'dog' && type !== 'cat')) {
                            item.classList.remove('d-none');
                        } else {
                            item.classList.add('d-none');
                        }
                    });

                    // Show/hide sections based on visible items
                    sections.forEach(section => {
                        const visibleItems = section.container.querySelectorAll('.animal-item:not(.d-none)');
                        if (visibleItems.length > 0) {
                            section.header.classList.remove('d-none');
                            section.container.classList.remove('d-none');
                        } else {
                            section.header.classList.add('d-none');
                            section.container.classList.add('d-none');
                        }
                    });
                });
            });

            // Handle edit pet button clicks
            document.querySelectorAll('.edit-pet-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const form = document.getElementById('editModal').querySelector('form');
                    form.reset();
                    // Manually clear file input as form.reset() may not clear it in all browsers
                    document.getElementById('edit_image').value = '';
                    const petData = JSON.parse(this.getAttribute('data-pet'));
                    // Populate the form with the new pet data
                    document.getElementById('edit_pet_id').value = petData.id;
                    document.getElementById('edit_name').value = petData.name;
                    document.getElementById('edit_breed').value = petData.breed;
                    document.getElementById('edit_description').value = petData.description;
                    document.getElementById('edit_type').value = petData.type.toLowerCase();
                    document.getElementById('edit_availability').checked = petData.availability == 1;
                    document.getElementById('edit_featured').checked = petData.featured == 1;
                    // Show the modal
                    const editModal = new bootstrap.Modal(document.getElementById('editModal'));
                    editModal.show();
                });
            });

            // Reset the form when the modal is hidden to prevent data duplication
            document.getElementById('editModal').addEventListener('hidden.bs.modal', function () {
                const form = this.querySelector('form');
                form.reset();
                // Manually clear file input
                document.getElementById('edit_image').value = '';
            });
        });
    </script>
</body>
</html>
