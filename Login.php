<?php
include 'config.php';

session_start();


$message = [];

if(isset($_POST['submit']))
{
    $email = $_POST['email'];
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $pass = $_POST['pass'];
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);

$select = $conn->prepare("SELECT * FROM `account` WHERE email = ?");
$select->execute([$email]);
$row = $select->fetch(PDO::FETCH_ASSOC);

if($select->rowCount() > 0 && password_verify($pass, $row['password'])){
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['user_email'] = $row['email'];
    if($row['user_type'] === 'admin'){
        header('location:admin_panel.php');
    } else {
        header('location:index.php');
    }
} else {
    $message[] = 'Invalid email or password!';
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LogIn - Pawradise Pet Adoption</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
         body {
            background-image: url('Background.svg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            font-family: 'Arial', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .form-container {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
        }
        .btn-primary {
            background-color: #ff6b6b;
            border-color: #ff6b6b;
        }
        .btn-primary:hover {
            background-color: #ff5252;
            border-color: #ff5252;
        }
        .message {
            margin: 10px;
            padding: 10px;
            background-color: #ffeaa7;
            color: #d63031;
            border: 1px solid #fdcb6e;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<?php
if(isset($message)){
    foreach($message as $msg){
        echo '<div class="message">
                <span>'.$msg.'</span>
                <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
              </div>';
    }
}
?>

<section class="form-container">
    <form action="" method="POST">
        <h3>Log In Now</h3>
        <input type="email" name="email" class="form-control mb-2" placeholder="Enter your email" required>
        <input type="password" name="pass" class="form-control mb-2" placeholder="Enter your password" required>
        <input type="submit" name="submit" value="Login Now" class="btn btn-primary w-100">
        <p class="text-center mt-3">Don't have an account? <a href="register.php">Register</a></p>
    </form>
</section>
</body>
</html>
