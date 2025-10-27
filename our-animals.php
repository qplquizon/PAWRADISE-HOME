<?php
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
include 'config.php';
session_start();

$is_admin = isset($_SESSION['user_id']) && isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';

try {

    $pets_query = $conn->prepare("
        SELECT p.* FROM pets p
        WHERE p.availability = 1
        AND p.id NOT IN (
            SELECT ar.pet_interest FROM adoption_requests ar WHERE ar.status = 'pending'
        )
        ORDER BY p.id DESC
    ");
    $pets_query->execute();
    $pets = $pets_query->fetchAll(PDO::FETCH_ASSOC);

    $pets = array_values(array_unique($pets, SORT_REGULAR));

} catch (PDOException $e) {
    $pets = [];
    $error_message = "Error fetching pets: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Animals - Pawradise Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>" />
    <link rel="stylesheet" href="our-animals.css?v=<?php echo time(); ?>" />
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

<div class="container py-5">
    <h1 class="text-center mb-4">Meet Our Lovely Animals</h1>

    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger text-center"><?php echo htmlspecialchars($error_message); ?></div>
    <?php endif; ?>


    <div class="d-flex justify-content-center mb-3">
        <div class="input-group" style="max-width: 400px;">
            <input type="text" class="form-control" id="search-input" placeholder="Search by name..." aria-label="Search animals">
            <button class="btn btn-outline-secondary" type="button" id="clear-search">Clear</button>
        </div>
    </div>


    <div class="d-flex justify-content-center mb-4">
        <div class="btn-group" role="group" aria-label="Animal Filter">
            <button type="button" class="btn btn-outline-primary filter-btn active" data-filter="all">All</button>
            <button type="button" class="btn btn-outline-primary filter-btn" data-filter="dog">Dogs</button>
            <button type="button" class="btn btn-outline-primary filter-btn" data-filter="cat">Cats</button>
            <button type="button" class="btn btn-outline-primary filter-btn" data-filter="other">Other Animals</button>
        </div>
    </div>

    <?php if (count($pets) > 0): ?>
        <div class="row g-4" id="animals-container">
            <?php foreach ($pets as $pet): ?>
                <div class="col-sm-6 col-md-4 col-lg-3 animal-item" data-type="<?php echo htmlspecialchars(strtolower($pet['type'] ?? 'other')); ?>">
                    <div class="card shadow-sm border-0 h-100">
                        <img src="<?php echo !empty($pet['image']) ? htmlspecialchars($pet['image']) : 'uploads/default-pet.png'; ?>"
                             class="card-img-top"
                             alt="<?php echo htmlspecialchars($pet['name']); ?>"
                             style="object-fit: cover; height: 220px;">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($pet['name']); ?></h5>
                            <p class="mb-1"><strong>Breed:</strong> <?php echo htmlspecialchars($pet['breed']); ?></p>
                            <p class="small text-muted mb-2"><?php echo htmlspecialchars($pet['description']); ?></p>
                            <span class="badge bg-success">Available</span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="text-center text-muted mt-4">No animals available at the moment. Please check back later!</p>
    <?php endif; ?>
</div>

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterButtons = document.querySelectorAll('.filter-btn');
        const animalItems = document.querySelectorAll('.animal-item');
        const searchInput = document.getElementById('search-input');
        const clearSearchBtn = document.getElementById('clear-search');


        function filterAnimals() {
            const searchTerm = searchInput.value.toLowerCase();
            const activeFilter = document.querySelector('.filter-btn.active').getAttribute('data-filter');

            animalItems.forEach(item => {
                const name = item.querySelector('.card-title').textContent.toLowerCase();
                const type = item.getAttribute('data-type');

                const matchesSearch = name.includes(searchTerm);
                const matchesFilter = activeFilter === 'all' || type === activeFilter;

                if (matchesSearch && matchesFilter) {
                    item.classList.remove('d-none');
                } else {
                    item.classList.add('d-none');
                }
            });
        }


        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                const filter = this.getAttribute('data-filter');

     
                filterButtons.forEach(btn => btn.classList.remove('active'));

                this.classList.add('active');


                filterAnimals();
            });
        });

 
        searchInput.addEventListener('input', filterAnimals);


        clearSearchBtn.addEventListener('click', function() {
            searchInput.value = '';
            filterAnimals();
        });
    });
</script>
</body>
</html>
