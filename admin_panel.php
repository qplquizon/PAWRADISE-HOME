<?php
// echo "PHP is working";
include 'config.php';
session_start();


$query = $conn->prepare("SELECT COUNT(*) as total_users FROM `account`");
$query->execute();
$result = $query->fetch(PDO::FETCH_ASSOC);
$total_users = $result['total_users'];


if(isset($_POST['add_pet'])){
    $name = $_POST['name'];
    $breed = $_POST['breed'];
    $description = $_POST['description'];
    $availability = isset($_POST['availability']) ? 1 : 0;
    $type = $_POST['type'];


    $image = '';
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_path = 'uploads/' . basename($image_name);
        if(move_uploaded_file($image_tmp, $image_path)){
            $image = $image_path;
        }
    }


    try {
        $insert = $conn->prepare("INSERT INTO `pets` (name, breed, description, image, availability, type) VALUES (?, ?, ?, ?, ?, ?)");
        $insert->execute([$name, $breed, $description, $image, $availability, $type]);
    } catch (PDOException $e) {
        // If column 'type' doesn't exist, insert without it
        if (strpos($e->getMessage(), 'Unknown column \'type\'') !== false) {
            $insert = $conn->prepare("INSERT INTO `pets` (name, breed, description, image, availability) VALUES (?, ?, ?, ?, ?)");
            $insert->execute([$name, $breed, $description, $image, $availability]);
        } else {
            throw $e;
        }
    }
}


if(isset($_POST['delete_pet'])){
    $pet_id = $_POST['delete_pet'];
    $delete = $conn->prepare("DELETE FROM `pets` WHERE id = ?");
    $delete->execute([$pet_id]);
}


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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Pawradise Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="styles.css" />
    <link rel="stylesheet" href="adopt.css" />
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

    <div class="container mt-5">
        <h1 class="mb-4">Admin Dashboard</h1>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Registrations</h5>
                        <p class="card-text display-4"><?php echo $total_users; ?></p>
                        <p class="card-text">Users have signed up</p>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <div class="container mt-5">
        <h2 class="mb-4">Manage Pets for Adoption</h2>
        <form action="admin_panel.php" method="POST" enctype="multipart/form-data" class="mb-4">
            <div class="mb-3">
                <label for="name" class="form-label">Pet Name</label>
                <input type="text" class="form-control" id="name" name="name" required />
            </div>
            <div class="mb-3">
                <label for="breed" class="form-label">Breed</label>
                <input type="text" class="form-control" id="breed" name="breed" required />
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Picture</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*" required />
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="availability" name="availability" checked />
                <label class="form-check-label" for="availability">Available for Adoption</label>
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Animal Type</label>
                <select class="form-control" id="type" name="type" required>
                    <option value="dog">Dog</option>
                    <option value="cat">Cat</option>
                </select>
            </div>
            <button type="submit" name="add_pet" class="btn btn-primary">Add Pet</button>
        </form>

        <h3 class="mb-3">Existing Pets</h3>
        <div class="row">
            <?php if(count($pets) > 0): ?>
                <?php foreach($pets as $pet): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <?php if(!empty($pet['image'])): ?>
                                <img src="<?php echo htmlspecialchars($pet['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($pet['name']); ?>" />
                            <?php else: ?>
                                <img src="uploads/default-pet.png" class="card-img-top" alt="Default Pet Image" />
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($pet['name']); ?></h5>
                                <p class="card-text"><strong>Breed:</strong> <?php echo htmlspecialchars($pet['breed']); ?></p>
                                <p class="card-text"><?php echo htmlspecialchars($pet['description']); ?></p>
                                <p class="card-text">
                                    <span class="badge <?php echo $pet['availability'] ? 'bg-success' : 'bg-secondary'; ?>">
                                        <?php echo $pet['availability'] ? 'Available' : 'Not Available'; ?>
                                    </span>
                                </p>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-pet-id="<?php echo htmlspecialchars($pet['id']); ?>" data-pet-name="<?php echo htmlspecialchars($pet['name']); ?>">Delete</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No pets found.</p>
            <?php endif; ?>
        </div>
    </div>

 
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete <span id="petName"></span>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" action="admin_panel.php" style="display:inline;">
                        <input type="hidden" name="delete_pet" id="deletePetId">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="adopt.js"></script>
    <script>
        function confirmLogout() {
            if (confirm("Are you sure you want to logout?")) {
                window.location.href = "logout.php";
            }
        }

        var deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var petId = button.getAttribute('data-pet-id');
            var petName = button.getAttribute('data-pet-name');
            var modalPetName = deleteModal.querySelector('#petName');
            var modalPetId = deleteModal.querySelector('#deletePetId');
            modalPetName.textContent = petName;
            modalPetId.value = petId;
        });
    </script>
</body>
</html>
