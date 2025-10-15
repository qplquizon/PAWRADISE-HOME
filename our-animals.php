<?php
include 'config.php';
session_start();

try {
    $pets_query = $conn->prepare("SELECT * FROM `pets`");
    $pets_query->execute();
    $pets = $pets_query->fetchAll(PDO::FETCH_ASSOC);
    // Default type to 'dog' if not set
    foreach ($pets as &$pet) {
        if (!isset($pet['type'])) {
            $pet['type'] = 'dog';
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
            <div class="row mb-4">
                <div class="col-md-12">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search by name, breed, or type...">
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-12 text-center">
                    <div class="form-check d-inline-block me-3">
                        <input class="form-check-input" type="checkbox" id="availableOnly" checked>
                        <label class="form-check-label" for="availableOnly">
                            Show only available for adoption
                        </label>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-12 text-center">
                    <button id="filterDogs" class="btn btn-primary me-2">Dogs</button>
                    <button id="filterCats" class="btn btn-secondary me-2">Cats</button>
                    <button id="filterOthers" class="btn btn-secondary">Other Animals</button>
                </div>
            </div>

            <?php
            $dogs = array_filter($pets, function($pet) { return $pet['type'] === 'dog'; });
            $cats = array_filter($pets, function($pet) { return $pet['type'] === 'cat'; });
            $others = array_filter($pets, function($pet) { return $pet['type'] === 'other'; });
            ?>

            <h3>Dogs</h3>
            <div class="row g-4" id="dogs-container">
                <?php if(count($dogs) > 0): ?>
                    <?php foreach($dogs as $pet): ?>
                        <div class="col-lg-4 col-md-6 animal-item" data-name="<?php echo htmlspecialchars(strtolower($pet['name'])); ?>" data-breed="<?php echo htmlspecialchars(strtolower($pet['breed'])); ?>" data-type="<?php echo htmlspecialchars(strtolower($pet['type'])); ?>" data-availability="<?php echo $pet['availability'] ? '1' : '0'; ?>">
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
                    <p>No dogs available at the moment.</p>
                <?php endif; ?>
            </div>

            <h3>Cats</h3>
            <div class="row g-4" id="cats-container">
                <?php if(count($cats) > 0): ?>
                    <?php foreach($cats as $pet): ?>
                        <div class="col-lg-4 col-md-6 animal-item" data-name="<?php echo htmlspecialchars(strtolower($pet['name'])); ?>" data-breed="<?php echo htmlspecialchars(strtolower($pet['breed'])); ?>" data-type="<?php echo htmlspecialchars(strtolower($pet['type'])); ?>" data-availability="<?php echo $pet['availability'] ? '1' : '0'; ?>">
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
                    <p>No cats available at the moment.</p>
                <?php endif; ?>
            </div>

            <h3>Other Animals</h3>
            <div class="row g-4" id="others-container">
                <?php if(count($others) > 0): ?>
                    <?php foreach($others as $pet): ?>
                        <div class="col-lg-4 col-md-6 animal-item" data-name="<?php echo htmlspecialchars(strtolower($pet['name'])); ?>" data-breed="<?php echo htmlspecialchars(strtolower($pet['breed'])); ?>" data-type="<?php echo htmlspecialchars(strtolower($pet['type'])); ?>" data-availability="<?php echo $pet['availability'] ? '1' : '0'; ?>">
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
    <script src="bootstrap.js"></script>
    <script src="our-animals.js" type="module"></script>
    <script>
        function confirmLogout() {
            if (confirm("Are you sure you want to logout?")) {
                window.location.href = "logout.php";
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const availableOnly = document.getElementById('availableOnly');
            const filterDogs = document.getElementById('filterDogs');
            const filterCats = document.getElementById('filterCats');
            const filterOthers = document.getElementById('filterOthers');
            const dogsContainer = document.getElementById('dogs-container');
            const catsContainer = document.getElementById('cats-container');
            const othersContainer = document.getElementById('others-container');

            function filterAndSort() {
                const searchTerm = searchInput.value.toLowerCase();

                // Get all items
                const allItems = Array.from(dogsContainer.querySelectorAll('.animal-item'))
                    .concat(Array.from(catsContainer.querySelectorAll('.animal-item')))
                    .concat(Array.from(othersContainer.querySelectorAll('.animal-item')));

                // Filter by search
                let filteredItems = allItems.filter(item => {
                    const name = item.dataset.name.toLowerCase();
                    const breed = item.dataset.breed.toLowerCase();
                    const type = item.dataset.type.toLowerCase();
                    return name.includes(searchTerm) || breed.includes(searchTerm) || type.includes(searchTerm);
                });

                // Filter by availability
                if (availableOnly.checked) {
                    filteredItems = filteredItems.filter(item => item.dataset.availability === '1');
                }

                // Separate by type
                const dogs = filteredItems.filter(item => item.dataset.type === 'dog');
                const cats = filteredItems.filter(item => item.dataset.type === 'cat');
                const others = filteredItems.filter(item => item.dataset.type === 'other');

                // Clear containers
                dogsContainer.innerHTML = '';
                catsContainer.innerHTML = '';
                othersContainer.innerHTML = '';

                // Append items
                dogs.forEach(item => dogsContainer.appendChild(item));
                cats.forEach(item => catsContainer.appendChild(item));
                others.forEach(item => othersContainer.appendChild(item));

                // Show/hide sections if no items
                document.querySelector('h3').nextElementSibling.style.display = dogs.length > 0 ? 'block' : 'none';
                document.querySelectorAll('h3')[1].nextElementSibling.style.display = cats.length > 0 ? 'block' : 'none';
                document.querySelectorAll('h3')[2].nextElementSibling.style.display = others.length > 0 ? 'block' : 'none';
            }

            searchInput.addEventListener('input', filterAndSort);
            availableOnly.addEventListener('change', filterAndSort);

            filterDogs.addEventListener('click', () => {
                dogsContainer.style.display = 'block';
                catsContainer.style.display = 'none';
                othersContainer.style.display = 'none';
                filterDogs.classList.add('btn-primary');
                filterDogs.classList.remove('btn-secondary');
                filterCats.classList.add('btn-secondary');
                filterCats.classList.remove('btn-primary');
                filterOthers.classList.add('btn-secondary');
                filterOthers.classList.remove('btn-primary');
            });

            filterCats.addEventListener('click', () => {
                dogsContainer.style.display = 'none';
                catsContainer.style.display = 'block';
                othersContainer.style.display = 'none';
                filterCats.classList.add('btn-primary');
                filterCats.classList.remove('btn-secondary');
                filterDogs.classList.add('btn-secondary');
                filterDogs.classList.remove('btn-primary');
                filterOthers.classList.add('btn-secondary');
                filterOthers.classList.remove('btn-primary');
            });

            filterOthers.addEventListener('click', () => {
                dogsContainer.style.display = 'none';
                catsContainer.style.display = 'none';
                othersContainer.style.display = 'block';
                filterOthers.classList.add('btn-primary');
                filterOthers.classList.remove('btn-secondary');
                filterDogs.classList.add('btn-secondary');
                filterDogs.classList.remove('btn-primary');
                filterCats.classList.add('btn-secondary');
                filterCats.classList.remove('btn-primary');
            });
        });
    </script>
</body>
</html>
