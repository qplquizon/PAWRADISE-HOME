<?php
include 'config.php';
session_start();

if(!isset($_SESSION['user_id'])){
    header('location:Login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$select = $conn->prepare("SELECT * FROM `account` WHERE id = ?");
$select->execute([$user_id]);
$user = $select->fetch(PDO::FETCH_ASSOC);

if($user['user_type'] !== 'admin'){
    header('location:index.php');
    exit();
}

// Fetch donations
$donations = $conn->query("SELECT * FROM donations ORDER BY date DESC")->fetchAll(PDO::FETCH_ASSOC);
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
                    <li class="nav-item"><a class="nav-link" href="index.php">HOME</a></li>
                    <li class="nav-item"><a class="nav-link" href="our-animals.html">OUR ANIMALS</a></li>
                    <li class="nav-item"><a class="nav-link" href="adopt.html">ADOPT</a></li>
                    <li class="nav-item"><a class="nav-link" href="donate.php">DONATE</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.html">ABOUT</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin_panel.php">ADMIN</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php" title="Logout">
                            Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="admin-dashboard py-5">
        <div class="container">
            <h1 class="text-center mb-5">Admin Dashboard</h1>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3>Donations</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(count($donations) > 0): ?>
                                        <?php foreach($donations as $donation): ?>
                                            <tr>
                                                <td><?php echo $donation['id']; ?></td>
                                                <td><?php echo htmlspecialchars($donation['name']); ?></td>
                                                <td><?php echo htmlspecialchars($donation['email']); ?></td>
                                                <td>$<?php echo number_format($donation['amount'], 2); ?></td>
                                                <td><?php echo $donation['date']; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center">No donations yet.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
